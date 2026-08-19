<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\Template\Engine;
use Bitrix\Iblock\Template\Entity\Section;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use Bitrix\Main\Text\Encoding;
use Sotbit\Seometa\Orm\ConditionTable;
use Sotbit\Seometa\Orm\SeometaUrlTable;
use Sotbit\Seometa\SeoMetaMorphy;
use Sotbit\Seometa\Tags;

global $SeoMetaWorkingConditions;
global $APPLICATION;
global $USER;

$moduleId = 'sotbit.seometa';
if (!Loader::includeModule($moduleId) || !Loader::includeModule('iblock')) {
    return false;
}

if (empty($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = '36000000';
}

if (empty($arParams['SORT'])) {
    $arParams['SORT'] = 'NAME';
}

$cacheTime = $arParams['CACHE_TIME'];
$cache_id = serialize([
    $arParams,
    $SeoMetaWorkingConditions,
    $APPLICATION->GetCurPage(false),
    $arParams['CACHE_GROUPS'] === 'N' ? false : $USER->GetGroups()
]);
$cacheDir = '/' . $moduleId . '.tags/';
$cache = Cache::createInstance();
$Tags = [];

if ($cache->initCache($cacheTime, $cache_id, $cacheDir)) {
    $Tags = $cache->getVars();
} elseif ($cache->startDataCache()) {
    $strict_relinking = false;
    $Conditions = [];
    $sections = Tags::findNeedSections($arParams['SECTION_ID'], $arParams['INCLUDE_SUBSECTIONS'], $arParams['INCLUDE_PARENTSECTIONS']); // list of all sections
    $SectionConditions = ConditionTable::GetConditionsBySections($sections); // list of all conditions by sections

    $tagsCategory = \Sotbit\Seometa\Orm\CategoryTagsTable::getById(1)->fetch();

    // if condition is active
    if ($SeoMetaWorkingConditions && is_array($SeoMetaWorkingConditions)) {
        foreach ($SeoMetaWorkingConditions as $SeoMetaWorkingCondition) {
            $wasSections = false;
            // if among all conditions by sections there is one that is active
            if ($SectionConditions[$SeoMetaWorkingCondition]) {
                if ($SectionConditions[$SeoMetaWorkingCondition]['STRICT_RELINKING'] == 'Y') {
                    $strict_relinking = true;
                }

                if (!empty($SectionConditions[$SeoMetaWorkingCondition]['SECTIONS'])) {
                    $wasSections = true;
                } else {
                    unset($SectionConditions[$SeoMetaWorkingCondition]);
                }
            }
        }
    }

    $WorkingConditions = ConditionTable::GetConditionsFromWorkingConditions($SeoMetaWorkingConditions); // conditions selected in relinking
    if (is_array($SectionConditions) && is_array($WorkingConditions)) {
        if (!$strict_relinking) {
            $Conditions = $SectionConditions;
        }

        // merge conditions selected in relinking with other
        foreach ($WorkingConditions as $key => $WorkingCondition) {
            $Conditions[$key] = $WorkingCondition;
        }
    } elseif (is_array($SectionConditions)) {
        $Conditions = $SectionConditions;
    } elseif ($WorkingConditions) {
        $Conditions = $WorkingConditions;
    }

    $TagsObject = new Tags();
    $currentUrl = $APPLICATION->GetCurPage(false);

    //<editor-fold desc="Exclude condition, if in enable HIDE_IN_SECTION and current url is section url">
    $sectionUrl = CIBlockSection::GetList(
        [],
        ['ID' => $arParams['SECTION_ID']],
        false,
        ['SECTION_PAGE_URL']
    )->GetNext()['SECTION_PAGE_URL'];

    if ($sectionUrl == $currentUrl) {
        $Conditions = array_filter($Conditions, fn($item) => !($item['HIDE_IN_SECTION'] == 'Y' && in_array($arParams['SECTION_ID'], $item['SECTIONS'])));
    }

    $filterResult = CSeoMeta::getFilterResult();

    //</editor-fold>
    if ($arParams['GENERATING_TAGS'] == 'Y') {
        $Tags = $TagsObject->GenerateTags($Conditions, array_keys($Conditions));
        if(is_array($Tags)) {
            foreach ($Tags as &$tag) {
                $tag = \Sotbit\Seometa\Orm\CategoryTagsTable::fillCategoryTags($Conditions[$tag['CONDITION_ID']], $tag, $tagsCategory);
            }
            unset($tag);
        }
    } else {
        $Tags = [];
        foreach ($Conditions as $item) {
            if ($item['TAG']) {
                $arrTags = SeometaUrlTable::getAllByCondition($item['ID']);
                foreach ($arrTags as &$arrTag) {

                    $arrTag['PROPERTIES'] = unserialize($arrTag['PROPERTIES']);
                    SeoMetaMorphy::init($arrTag['section_id'], $arrTag['PROPERTIES']);
                    $title = SeoMetaMorphy::processMorphy($item['TAG']);

                    if (!empty($title)) {
                        $arrTag['TITLE'] = SeoMetaMorphy::convertMorphy($title);;
                    }

                    if (empty($arrTag['TITLE'])) {
                        $arrTag['TITLE'] = $title;
                    }

                    $arrTag['URL'] = $arrTag['ACTIVE'] == 'Y' ? $arrTag['NEW_URL'] : $arrTag['REAL_URL'];
                    $arrTag = \Sotbit\Seometa\Orm\CategoryTagsTable::fillCategoryTags($Conditions[$arrTag['CONDITION_ID']], $arrTag, $tagsCategory);
                }

                if (is_array($arrTags)) {
                    $Tags = array_merge($Tags, $arrTags);
                }
            }
        }
    }

    if (empty($Tags)) {
        $Tags = [];
        $cache->endDataCache($Tags);
        return $this->IncludeComponentTemplate();
    }

    if ($strict_relinking) {
        foreach ($Tags as $key => $tag) {
            if ($tag['URL'] == $currentUrl) {
                unset($Tags[$key]);
            }
        }

        $Tags = array_values($Tags);
    }

    $likeFilter = \Bitrix\Main\Config\Option::get($moduleId, 'TAGS_FILTER', 'N', SITE_ID);

    $arFilterResult = [];
    $arFilterRangeResult = [];
    if ($likeFilter === 'Y') {
        foreach ($filterResult['ITEMS'] as $itemKey => $item) {
            foreach ($item['VALUES'] as $key => $value) {
                if ($key === 'MAX' || $key === 'MIN') {
                    if ($item['CODE']) {
                        $arFilterRangeResult[$item['CODE']][$key] = $value['VALUE'];
                    } else {
                        $arFilterRangeResult[$itemKey][$key] = $value['VALUE'];
                    }

                } else {
                    $arFilterResult[$item['CODE']][] = $value['LIST_VALUE_NAME'] ?: ($value['LIST_VALUE'] ?: $value['VALUE']);
                }
            }
        }
    }

    if (!empty($Tags)) {
        foreach ($Tags as $keyTag => $tag) {
            if ($tag['SITE_ID'] !== SITE_ID) {
                unset($Tags[$keyTag]);
            }

            if ($likeFilter === 'Y' && ($arFilterResult || $arFilterRangeResult)) {
                $unset = true;
                foreach ($tag['PROPERTIES'] as $code => $prop) {
                    if ($arFilterRangeResult[$code]) {
                        foreach ($prop as $value) {
                            if ((float)$arFilterRangeResult[$code]['MIN'] <= (float)$value && (float)$arFilterRangeResult[$code]['MAX'] >= (float)$value) {
                                $unset = false;
                                break;
                            }
                        }
                    }

                    if ($arFilterResult[$code]) {
                        $prop = htmlspecialcharsback($prop);
                        if (array_intersect($prop, $arFilterResult[$code])) {
                            $unset = false;
                        }
                    }
                }

                if ($unset) {
                    unset($Tags[$keyTag]);
                }
            }
        }
        $Tags = array_values($Tags);
    }

    $currentUrl = CSeoMeta::encodeRealUrl($currentUrl);

    if ($arParams['GENERATING_TAGS'] == 'Y') {
        $Tags = $TagsObject->ReplaceChpuUrls($Tags);
    }

    $curPage = array_search(
        $currentUrl,
        array_combine(array_keys($Tags), array_column($Tags, 'URL'))
    );
    if ($curPage === false) {
        $curPage = array_search(
            $currentUrl,
            array_combine(array_keys($Tags), array_column($Tags, 'REAL_URL'))
        );
    }

    if ($curPage !== false) {
        unset($Tags[$curPage]);
    }

    $Tags = $TagsObject->SortTags($Tags, $arParams['SORT'], $arParams['SORT_ORDER']);
    if($componentTemplate !== 'tagscategory') {
        $Tags = $TagsObject->CutTags($Tags, $arParams['CNT_TAGS']);
    } else {
        $arParams['CNT_TAGS'] = $arParams['CNT_TAGS'] ?: 10;
    }


    unset($Conditions);
    $cache->endDataCache($Tags);
}

if ($arParams['AJAX'] === 'Y') {
    $arResult['ITEMS'] = array_map(function ($arrVal) {
        $arrVal['URL'] = $arrVal['URL'] . '?ajaxTag=1';
        return $arrVal;
    }, $Tags);
} else {
    $arResult['ITEMS'] = $Tags;
}

unset($Tags);
$this->IncludeComponentTemplate();
?>
