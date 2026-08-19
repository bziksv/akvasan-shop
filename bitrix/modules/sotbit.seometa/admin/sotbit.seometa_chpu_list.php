<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\Emoji;
use Bitrix\Main\Type;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Web\Uri;
use Sotbit\Seometa\Helper\SitemapRuntime;
use Sotbit\Seometa\Orm\ConditionTable;
use Sotbit\Seometa\SeoMetaMorphy;
use Sotbit\Seometa\Orm\SectionUrlTable;
use Sotbit\Seometa\Orm\SeometaUrlTable;
use Bitrix\Iblock\Template\Entity\Section;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

global $APPLICATION;

CJSCore::Init(array("jquery3"));
$error = '';
$id_module='sotbit.seometa';
$arResult = [];
Loc::loadMessages(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("sotbit.seometa");
if ($POST_RIGHT == "D" || !Loader::includeModule($id_module) || !Loader::includeModule('iblock')) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$sTableID = "b_sotbit_seometa_chpu";
$oSort = new CAdminSorting($sTableID, 'ID', 'asc');
$lAdmin = new CAdminUiList($sTableID, $oSort);

$parentID = 0;
if (!empty($_REQUEST["parent"])) {
    $parentID = $_REQUEST["parent"];
}

$ParentUrl = '';
if (!empty($parentID)) {
    $ParentUrl = "&section=" . $parentID;
}

$childCategory = SectionUrlTable::searchChildSection([$parentID]);

$FilterArr = [
    [
        "id" => "ID",
        "name" =>  Loc::getMessage("SEO_META_ID"),
        "type" => "number",
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ],
    [
        "id" => "ACTIVE",
        "name" => Loc::getMessage("SEO_META_ACTIVE"),
        "type" => "list",
        "items" => array(
            "Y" => Loc::getMessage("IBLOCK_YES"),
            "N" => Loc::getMessage("IBLOCK_NO")
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "IN_SITEMAP",
        "name" => Loc::getMessage("SEO_META_IN_SITEMAP"),
        "type" => "list",
        "items" => array(
            "Y" => Loc::getMessage("IBLOCK_YES"),
            "N" => Loc::getMessage("IBLOCK_NO")
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "NAME",
        "name" =>  Loc::getMessage("SEO_META_NAME"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "SITE_ID",
        "name" =>  Loc::getMessage("SEO_META_SITE_ID"),
        "type" => "list",
        "items" => \Sotbit\Seometa\Helper\AdminSection\Tools::getSitesChpuList($childCategory),
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "REAL_URL",
        "name" =>  Loc::getMessage("SEO_META_REAL_URL"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "NEW_URL",
        "name" =>  Loc::getMessage("SEO_META_NEW_URL"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "iblock_id",
        "name" =>  Loc::getMessage("SEO_META_IBLOCK"),
        "type" => "list",
        "items" => \Sotbit\Seometa\Helper\AdminSection\Tools::getIblockChpuList($childCategory),
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "section_id",
        "name" =>  Loc::getMessage("SEO_META_SECTION"),
        "type" => "list",
        "items" => \Sotbit\Seometa\Helper\AdminSection\Tools::getSectionsChpuList($childCategory),
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
];

$arFilter = [];

$lAdmin->AddFilter(array_merge($FilterArr, [
    [
        "id" => "%NAME",
        "name" => 'find',
        "type" => "string",
        "quickSearch" => "",
    ]
]), $arFilter);


$arIDs = array_column($FilterArr, "id");

foreach ($arFilter as $key => $item) {
    $findKey = array_search($key, $arIDs);
    if($findKey && $FilterArr[$findKey]['type'] === 'string') {
        $arFilter[$key] = "%" . $item . "%";
    }
}

if ($arFilter['section_id'] === 'top') {
    $arFilter['section_id'] = 0;
}

$arFilter['CATEGORY_ID'] = $arFilter ? $childCategory : $parentID;

if ($lAdmin->EditAction() && isset($FIELDS) && is_array($FIELDS)) {
    foreach ($FIELDS as $ID => $arFields) {
        $TYPE = mb_substr($ID, 0, 1);
        $ID = intval(mb_substr($ID,1));
        if (!$lAdmin->IsUpdated($ID)) {
            continue;
        }

        if ($ID > 0) {
            foreach ($arFields as $key => $value) {
                $arData[$key] = $value;
            }
            if ($TYPE === "P") {
                $result = SeometaUrlTable::update($ID,$arData);
            } else {
                $arData['DATE_CHANGE'] = new Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
                $result = SectionUrlTable::update($ID, $arData);
            }
            if (!$result || !$result->isSuccess()) {
                $lAdmin->AddGroupError(Loc::getMessage("SEO_META_SAVE_ERROR") . " " . Loc::getMessage("SEO_META_UPDATE_ERROR"),
                    $ID);
            }
        } else {
            $lAdmin->AddGroupError(Loc::getMessage("SEO_META_SAVE_ERROR") . " " . Loc::getMessage("SEO_META_NO_ZAPIS"),
                $ID);
        }
    }
}

if ($arID = $lAdmin->GroupAction()) {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = SeometaUrlTable::getList([
            'select' => [
                'ID',
                'NAME',
                'ACTIVE',
                'REAL_URL',
                'NEW_URL',
                'DATE_CHANGE'
            ],
            'filter' => $arFilter,
            'order' => [$by => $order],
        ]);

        while ($arRes = $rsData->Fetch()) {
            $arRes["T"] = "S";
            $arRes['ID'] = "P" . $arRes['ID'];
            $arID[] = $arRes['ID'];
        }

        if (!isset($filter)) {
            $filter = [];
        }

        $rsSection = SectionUrlTable::getList([
            'limit' => null,
            'offset' => null,
            'select' => ["*"],
            "filter" => $filter
        ]);
        while($arSection = $rsSection->Fetch()) {
            $arSection["T"]="S";
            $arSection['ID']="S".$arSection['ID'];
            $arID[]=$arSection;
        }
    }

    if ($lAdmin->IsGroupActionToAll()) {
        $allDBUrls = SeometaUrlTable::query()->setFilter(['CATEGORY_ID' => $parentID])->addSelect('ID')->exec();
        $arID = [];
        while ($chpu = $allDBUrls->fetch()) {
            $arID[] = 'P' . $chpu['ID'];
        }

        $allDBSectionsUrls = SectionUrlTable::query()->setFilter(['PARENT_CATEGORY_ID' => $parentID])->addSelect('ID')->exec();
        while ($section = $allDBSectionsUrls->fetch()) {
            $arID[] = 'S' . $section['ID'];
        }
    }

    foreach ($arID as $ID) {
        $TYPE = mb_substr($ID, 0, 1);
        $ID = intval(mb_substr($ID,1));

        if (mb_strlen($ID) <= 0) {
            continue;
        }

        $ID = IntVal($ID);
        switch ($_REQUEST['action']) {
            case "delete":
                if ($TYPE == "P") {
                    $result = SeometaUrlTable::delete($ID);
                } else {
                    $result = SectionUrlTable::deleteSections($ID, true);
                }
                if (!$result->isSuccess()) {
                    $lAdmin->AddGroupError(Loc::getMessage("SEO_META_DEL_ERROR") . " " . Loc::getMessage("SEO_META_NO_ZAPIS"),
                        $ID);
                }
                break;
            case "activate":
            case "deactivate":
                $arFields["ACTIVE"] = ($_REQUEST['action'] == "activate" ? "Y" : "N");
                if ($TYPE == "P") {
                    $result = SeometaUrlTable::update($ID,
                        [
                            'ACTIVE' => $arFields["ACTIVE"],
                        ]);
                } else {
                    $result = SectionUrlTable::update($ID,
                        [
                            'ACTIVE' => $arFields["ACTIVE"],
                        ]);
                }
            if (!$result || !$result->isSuccess()) {
                $lAdmin->AddGroupError(Loc::getMessage("SEO_META_SAVE_ERROR") . " " . Loc::getMessage("SEO_META_NO_ZAPIS"),
                    $ID);
            }
            break;
            case "copy":
                if ($TYPE == "P") {
                    $chpu = SeometaUrlTable::getById($ID);
                    $arFields = [
                        'SORT' => $chpu['SORT'],
                        'CONDITION_ID' => $chpu['CONDITION_ID'],
                        'ACTIVE' => 'N',
                        'REAL_URL' => $chpu['REAL_URL'],
                        'NEW_URL' => $chpu['NEW_URL'],
                        'CATEGORY_ID' => $chpu['CATEGORY_ID'],
                        'NAME' => $chpu['NAME'],
                        'PROPERTIES' => $chpu['PROPERTIES'],
                        'iblock_id' => $chpu['iblock_id'],
                        'section_id' => $chpu['section_id'],
                        'PRODUCT_COUNT' => $chpu['PRODUCT_COUNT'],
                        'IN_SITEMAP' => 'N',
                    ];
                    $result = SeometaUrlTable::addForCopy($arFields);
                } else {
                    $section = SectionUrlTable::getById($ID)->fetch();
                    $date = new Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
                    $arFields = [
                        'DATE_CHANGE' => $date,
                        'DATE_CREATE' => $date,
                        'ACTIVE' => 'Y',
                        'SORT' => $section['SORT'],
                        'NAME' => $section['NAME'],
                        'DESCRIPTION' => $section['DESCRIPTION'],
                        'PARENT_CATEGORY_ID' => $section['PARENT_CATEGORY_ID'],
                    ];
                    $result = SectionUrlTable::add($arFields);
                }

                if ($result && $result->isSuccess()) {
                    $ID = $result->getId();
                } else {
                    $errorMess = is_bool($result)
                        ? Loc::getMessage('SEOMETA_ERROR_CHPU_COPY')
                        : implode('<br>',$result->getErrorMessages());
                    $lAdmin->AddGroupError($errorMess, $ID);
                }

                break;
        }
    }
}

$show = "all";
if (isset($_REQUEST["show_sp"]) && $_REQUEST["show_sp"] == "all") {
    unset($arFilter["CATEGORY_ID"]);
    $show = "all";
} elseif (isset($_REQUEST["show_sp"]) && $_REQUEST["show_sp"] == "section") {
    $show = "section";
    unset($arFilter["CATEGORY_ID"]);
}

$filter = $arFilter;
if ($show == "all" || $show == "section") {
    $sectionFilter = [];
    if (isset($arFilter["CATEGORY_ID"])) {
        $sectionFilter["PARENT_CATEGORY_ID"] = $arFilter["CATEGORY_ID"];
    }

    $rsSection = SectionUrlTable::getList([
        'select' => ["*"],
        'limit' => null,
        'offset' => null,
        'filter' => $sectionFilter
    ]);
    while ($arSection = $rsSection->Fetch()) {
        $arSection["T"] = "S";
        $arResult[] = $arSection;
    }
    unset($rsSection);
}

if (!isset($arFilter['CATEGORY_ID'])) {
    $arFilter['CATEGORY_ID'] = 0;
}

$rsData = SeometaUrlTable::getList([
    'select' => [
        'ID',
        'NAME',
        'CONDITION_ID',
        'CATEGORY_ID',
        'ACTIVE',
        'REAL_URL',
        'NEW_URL',
        'IN_SITEMAP',
        'iblock_id',
        'section_id',
        'PRODUCT_COUNT',
        'DATE_CHANGE',
        'PROPERTIES',
        'SITE_ID',
        'SORT',
        'CONDITION_NAME' => 'PARENT_CONDITION.NAME',
        'CONDITION_META' => 'PARENT_CONDITION.META',
        'CHPU_META' => 'CHPU_SEODATA.SEOMETA_DATA',
    ],
    'filter' => $arFilter,
    'order' => [$by => $order],
]);

if (isset($rsData)) {
    $keyURL = array_flip(['REAL_URL', 'NEW_URL']);
    while ($arRes = $rsData->Fetch()) {
        $arRes["T"] = "P";
        if($arKey = array_intersect_key($arRes, $keyURL)){
            foreach ($arKey as $urlKey => $url){
                $url = rawurldecode($url);
                $url = Encoding::convertEncoding($url, "utf-8", LANG_CHARSET, $error);
                $arRes[$urlKey] = $url;
            }
        }
        $arResult[] = $arRes;
    }
}

$rs = new CDBResult;
$rs->InitFromArray($arResult);
$rsData = new CAdminUiResult($rs, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("SEO_META_NAV")));
$lAdmin->AddHeaders([
    [
        "id" => "ID",
        "content" => Loc::getMessage("SEO_META_TABLE_ID"),
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ],
    [
        "id" => "NAME",
        "content" => Loc::getMessage("SEO_META_TABLE_TITLE"),
        "sort" => "NAME",
        "default" => true,
    ],
    [
        "id" => "SORT",
        "content" => Loc::getMessage("SEO_META_TABLE_SORT"),
        "sort" => "SORT",
        "default" => true,
    ],
    [
        "id" => "CONDITION_ID",
        "content" => Loc::getMessage("SEO_META_TABLE_CONDITION_ID"),
        "sort" => "CONDITION_ID",
        "default" => true,
    ],
    [
        "id" => "META_TITLE",
        "content" => 'META_TITLE',
        "default" => true,
    ],
    [
        "id" => "META_KEYWORDS",
        "content" => 'META_KEYWORDS',
        "default" => true,
    ],
    [
        "id" => "META_DESCRIPTION",
        "content" => 'META_DESCRIPTION',
        "default" => true,
    ],
    [
        "id" => "ACTIVE",
        "content" => Loc::getMessage("SEO_META_TABLE_ACTIVE"),
        "sort" => "ACTIVE",
        "default" => true,
    ],
    [
        "id"    =>"SITE_ID",
        "content"  =>Loc::getMessage("SEO_META_TABLE_SITE_ID"),
        "sort" => "SITE_ID",
        "default"  =>true,
    ],
    [
        "id" => "REAL_URL",
        "content" => Loc::getMessage("SEO_META_TABLE_REAL_URL"),
        "sort" => "REAL_URL",
        "default" => true,
    ],
    [
        "id" => "NEW_URL",
        "content" => Loc::getMessage("SEO_META_TABLE_NEW_URL"),
        "sort" => "NEW_URL",
        "default" => true,
    ],
    [
        "id" => "IN_SITEMAP",
        "content" => Loc::getMessage("SEO_META_TABLE_IN_SITEMAP"),
        "sort" => "IN_SITEMAP",
        "default" => true,
    ],
    [
        "id" => "iblock_id",
        "content" => Loc::getMessage("SEO_META_TABLE_IBLOCK_ID"),
        "sort" => "iblock_id",
        "default" => true,
    ],
    [
        "id" => "section_id",
        "content" => Loc::getMessage("SEO_META_TABLE_SECTION_ID"),
        "sort" => "section_id",
        "default" => true,
    ],
    [
        "id" => "PRODUCT_COUNT",
        "content" => Loc::getMessage("SEO_META_TABLE_PRODUCT_COUNT"),
        "sort" => "PRODUCT_COUNT",
        "default" => true,
    ],
    [
        "id" => "DATE_CHANGE",
        "content" => Loc::getMessage("SEO_META_TABLE_DATE_CHANGE"),
        "sort" => "DATE_CHANGE",
        "default" => true,
    ],
    [
        "id" => "PROPERTIES",
        "content" => Loc::getMessage("SEO_META_TABLE_PROPERTIES"),
        "default" => true,
    ],
]);

if ($parentID > 0) {
    $Section = SectionUrlTable::getById($parentID)->Fetch();
    $aContext = [
        [
            "TEXT" => Loc::getMessage("SEO_META_POST_ADD_TEXT"),
            "LINK" => "sotbit.seometa_chpu_edit.php?lang=" . LANG . $ParentUrl,
            "TITLE" => Loc::getMessage("SEO_META_POST_ADD_TITLE"),
            "ICON" => "btn_new",
        ],
        [
            "TEXT" => Loc::getMessage("SEO_META_SECTION_ADD"),
            "LINK" => "sotbit.seometa_section_chpu_edit.php?parent=" . $parentID . "&lang=" . LANG,
            "TITLE" => Loc::getMessage("SEO_META_SECTION_ADD"),
            "ICON" => "btn_sect_new",
        ],
        [
            "TEXT" => Loc::getMessage("SEO_META_SECTION_UP"),
            "LINK" => "sotbit.seometa_chpu_list.php?parent=" . $Section['PARENT_CATEGORY_ID'] . "&lang=" . LANG,
            "TITLE" => Loc::getMessage("SEO_META_SECTION_UP"),
            "ICON" => "btn_sect_new",
        ],
        [
            "TEXT"=>Loc::getMessage("SEO_META_SECTION_EXCEL_DOWNLOAD"),
            "LINK"=>"javascript:exportCHPU();",
            "TITLE"=>Loc::getMessage("SEO_META_SECTION_EXCEL_DOWNLOAD"),
            "ICON"=>"btn_download",
        ],
        [
            "TEXT"=>Loc::getMessage("SEO_META_SECTION_EXCEL_UPLOAD"),
            "LINK"=>"sotbit.seometa_import_excel.php?lang=" . LANG . "&entity=chpu",
            "TITLE"=>Loc::getMessage("SEO_META_SECTION_EXCEL_UPLOAD"),
            "ICON"=>"btn_upload",
        ],
    ];
    $row =& $lAdmin->AddRow(".", ["NAME" => Loc::getMessage("SEO_META_SECTION_UP")]);
    $sectionUrl = "/bitrix/admin/sotbit.seometa_chpu_list.php?lang=". LANG . "&parent=". $Section['PARENT_CATEGORY_ID'];
    $showField = "<a href=\"". $sectionUrl ."\"><span class=\"adm-submenu-item-link-icon fileman_icon_folder_up\" alt=\"".Loc::getMessage("SEO_META_SECTION_UP")."\"></span>&nbsp;<a href=\"".$sectionUrl."\">..</a>";
    $row->AddField("NAME", $showField);
    $row->AddField("LOGIC_NAME", $showField);
    $row->AddField("SIZE", "");
    $row->AddField("DATE", "");
    $row->AddField("TYPE", "");
    $row->AddField("PERMS_B", "");
} else {
    $aContext = [
        [
            "TEXT" => Loc::getMessage("SEO_META_POST_ADD_TEXT"),
            "LINK" => "sotbit.seometa_chpu_edit.php?lang=" . LANG . $ParentUrl,
            "TITLE" => Loc::getMessage("SEO_META_POST_ADD_TITLE"),
            "ICON" => "btn_new",
        ],
        [
            "TEXT" => Loc::getMessage("SEO_META_SECTION_ADD"),
            "LINK" => "sotbit.seometa_section_chpu_edit.php?parent=" . $parentID . "&lang=" . LANG,
            "TITLE" => Loc::getMessage("SEO_META_SECTION_ADD"),
            "ICON" => "btn_sect_new",
        ],
        [
            "TEXT"=>Loc::getMessage("SEO_META_SECTION_EXCEL_DOWNLOAD"),
            "LINK"=>"javascript:exportCHPU();",
            "TITLE"=>Loc::getMessage("SEO_META_SECTION_EXCEL_DOWNLOAD"),
            "ICON"=>"btn_download1",
        ],
        [
            "TEXT"=>Loc::getMessage("SEO_META_SECTION_EXCEL_UPLOAD"),
            "LINK"=>"sotbit.seometa_import_excel.php?lang=" . LANG . "&entity=chpu",
            "TITLE"=>Loc::getMessage("SEO_META_SECTION_EXCEL_UPLOAD"),
            "ICON"=>"btn_upload",
        ],
    ];
}

$condSelectAr = [
  0 => Loc::getMessage('SEO_META_CONDITION_NOT_CHOOSE'),
];

$conds = ConditionTable::query()->setSelect(['ID', 'NAME'])->fetchAll();

foreach ($conds as $value) {
    $condSelectAr[$value['ID']] = $value['ID'] . ' - ' . $value['NAME'];
}

$lAdmin->AddAdminContextMenu($aContext, false, false);
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/sotbit.seometa_chpu_list.php'));
while ($arRes = $rsData->NavNext(true, "f_")) {
    $row =& $lAdmin->AddRow($f_T.$f_ID, $arRes);
    $row->AddInputField("NAME", ["size"=>20]);
    $row->AddCheckField("ACTIVE");

    if ($f_T == "S") {
        $sectionUrl ='/bitrix/admin/sotbit.seometa_chpu_list.php?parent=' . $f_ID   . '&lang=' . LANG;
        $row->AddViewField("NAME",
            '<a href="'. $sectionUrl .'" class="adm-list-table-icon-link" title="'
            . Loc::getMessage("IBLIST_A_LIST")
            . '"><span class="adm-submenu-item-link-icon adm-list-table-icon iblock-section-icon"></span><span class="adm-list-table-link">'
            . $f_NAME . '</span></a>'
        );
    } else {
        $iblock = CIBlock::GetByID($arRes['iblock_id'])->fetch();
        $section = CIBlockSection::GetByID($arRes['section_id'])->fetch();
        $props = unserialize($arRes['PROPERTIES']);
        $pr = '';
        if (is_array(($props))) {
            foreach ($props as $code => $value) {
                $name = CIBlockProperty::GetByID($code)->fetch();
                $pr .= $name['NAME'] . ' - ' . implode(', ', $value) . '; ';
            }
            $row->AddViewField("PROPERTIES", $pr);
        }

        SeoMetaMorphy::init($arRes['section_id'], $props);
        $condMeta = unserialize($arRes['CONDITION_META']);
        $chpuMeta = unserialize($arRes['CHPU_META']);

        $metaTitle = '';
        $metaKeywords = '';
        $metaDescription = '';

        if ($condMeta) {
            $metaTitle = $condMeta['ELEMENT_TITLE'] ? SeoMetaMorphy::processMorphy($condMeta['ELEMENT_TITLE']) : '';
            $metaKeywords = $condMeta['ELEMENT_KEYWORDS'] ? SeoMetaMorphy::processMorphy($condMeta['ELEMENT_KEYWORDS']) : '';
            $metaDescription = $condMeta['ELEMENT_DESCRIPTION'] ? SeoMetaMorphy::processMorphy($condMeta['ELEMENT_DESCRIPTION']) : '';
        }

        if ($chpuMeta) {
            $metaTitle = $chpuMeta['ELEMENT_TITLE_REPLACE'] === 'Y' ? $chpuMeta['ELEMENT_TITLE'] : $metaTitle;
            $metaKeywords = $chpuMeta['ELEMENT_KEYWORDS_REPLACE'] === 'Y' ? $chpuMeta['ELEMENT_KEYWORDS'] : $metaKeywords;
            $metaDescription = $chpuMeta['ELEMENT_DESCRIPTION_REPLACE'] === 'Y' ? $chpuMeta['ELEMENT_DESCRIPTION'] : $metaDescription;
        }

        $row->AddViewField("iblock_id", '<a target="_blank" href="iblock_edit.php?type='.$iblock['IBLOCK_TYPE_ID'].'&lang='.LANG.'&ID='.$arRes['iblock_id'].'&admin=Y">'.$iblock['NAME'].'</a>');
        $row->AddViewField(
            "section_id",
            $section ? '<a target="_blank" href="iblock_section_edit.php?IBLOCK_ID='.$arRes['iblock_id'].'&type='.$iblock['IBLOCK_TYPE_ID'].'&ID='.$arRes['section_id'].'&lang='.LANG.'&find_section_section='.$section['IBLOCK_SECTION_ID'].'">'.$section['NAME'].'</a>' : '<span>'.Loc::getMessage('SEO_META_TABLE_SECTION_TOP_LEVEL').'</span>'
        );
        $row->AddViewField("NAME", '<a href="sotbit.seometa_chpu_edit.php?ID='.$f_ID.'&lang='.LANG.$ParentUrl.'">'.htmlspecialcharsback($f_NAME).'</a>');
        $row->AddSelectField('CONDITION_ID', $condSelectAr);
        $row->AddSelectField('SITE_ID', array_column(\Bitrix\Main\SiteTable::query()->addSelect('LID')->fetchAll(), 'LID','LID'));
        $row->AddViewField("CONDITION_ID",
            $arRes['CONDITION_ID'] ?
                '<a href="/bitrix/admin/sotbit.seometa_edit.php?ID=' . $arRes['CONDITION_ID'] . '&lang=' . LANG . '" target="_blank">#' . $arRes['CONDITION_ID'] . ' - ' . $arRes['CONDITION_NAME'] . '</a>' : '');
        $row->AddViewField("IN_SITEMAP", $f_IN_SITEMAP == 'Y' ? Loc::getMessage("SEO_META_POST_YES") : Loc::getMessage("SEO_META_POST_NO"));
        $row->AddViewField("DATE_CHANGE", $arRes['DATE_CHANGE']);
        $row->AddInputField("SORT");
        $row->AddInputField("REAL_URL");
        $row->AddInputField("NEW_URL");
        $row->AddViewField('META_TITLE',  Emoji::decode($metaTitle));
        $row->AddViewField('META_KEYWORDS',Emoji::decode($metaKeywords));
        $row->AddViewField('META_DESCRIPTION', Emoji::decode($metaDescription));
    }

    $arActions = [];
    if ($f_T == 'P') {
        $arActions[] = [
            "ICON" => "edit",
            "DEFAULT" => true,
            "TEXT" => Loc::getMessage("SEO_META_EDIT"),
            "ACTION" => $lAdmin->ActionRedirect("sotbit.seometa_chpu_edit.php?ID=" . $f_ID . $ParentUrl)
        ];
    } else {
        $arActions[] = [
            "ICON" => "edit",
            "DEFAULT" => true,
            "TEXT" => Loc::getMessage("SEO_META_EDIT"),
            "ACTION" => $lAdmin->ActionRedirect("sotbit.seometa_section_chpu_edit.php?ID=" . $f_ID)
        ];
    }

    if ($POST_RIGHT >= "W") {
        $arActions[] = [
            "ICON" => "delete",
            "TEXT" => Loc::getMessage("SEO_META_DEL"),
            "ACTION" => "if(confirm('" . Loc::getMessage('SEO_META_DEL_CONF') . "')) " . $lAdmin->ActionDoGroup($f_T . $f_ID, "delete")
        ];
        $arActions[] = [
            "ICON" => "copy",
            "DEFAULT" => true,
            "TEXT" => Loc::getMessage("SEO_META_COPY"),
            "ACTION" => $lAdmin->ActionDoGroup($f_T . $f_ID, "copy")
        ];
    }

    $arActions[] = ["SEPARATOR" => true];
    if (is_set($arActions[count($arActions) - 1], "SEPARATOR")) {
        unset($arActions[count($arActions) - 1]);
    }
    $row->AddActions($arActions);
}

$lAdmin->AddFooter(
    [
        [
            "title" => Loc::getMessage("SEO_META_LIST_SELECTED"),
            "value" => $rsData->SelectedRowsCount()
        ],
        [
            "counter" => true,
            "title" => Loc::getMessage("SEO_META_LIST_CHECKED"),
            "value" => "0"
        ],
    ]
);

$lAdmin->AddGroupActionTable([
    "delete" => Loc::getMessage("SEO_META_LIST_DELETE"),
    "activate" => Loc::getMessage("SEO_META_LIST_ACTIVATE"),
    "deactivate" => Loc::getMessage("SEO_META_LIST_DEACTIVATE"),
    "copy" => Loc::getMessage("SEO_META_LIST_COPY"),
    "edit" => Loc::getMessage("SEO_META_LIST_EDIT"),
    "for_all" => true
]);

$lAdmin->CheckListMode();
$APPLICATION->SetTitle(Loc::getMessage("SEO_META_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if (CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO_END")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

?>
    <style>
        #bx-admin-prefix .bx-core-popup-menu-no-icons .bx-core-popup-menu-item-text {
            padding-left: 40px;
        }

        .bx-core-popup-menu-item-icon {
            display: block !important;
        }

        #menu-popup-CONDITION_ID_control_menu {
            width: 450px;
        }
    </style>
    <script>
        function exportCHPU(offset = 0, limit = 100, newFile = 1, count = 0, totalCount = 0) {
            const gridFilter = BX.Main.filterManager.getById('<?=$sTableID?>');
            const node = BX('seochpu_export');
            if(node.style.display === 'block'){
                const nodeProgress = BX('sitemap_progress');
                const nodeProgressStart = BX('sitemap_progress_start');
                nodeProgress.innerHTML = nodeProgressStart.innerHTML;
            }else{
                node.style.display = 'block';
            }
            const exportCHPU = BX.ajax.runAction('sotbit:seometa.excelExportImport.exportCHPU', {
                data: {
                    offset,
                    limit,
                    newFile,
                    count,
                    totalCount,
                    gridFilter: {
                        ID: gridFilter.getParam('FILTER_ID'),
                        FIELDS: <?=\Bitrix\Main\Web\Json::encode($FilterArr)?>,
                    }
                }
            }).then(response => {
                let count = response.data.COUNT;
                if (count > 0) {
                    newFile = 0;
                    const nodeProgressStart = BX('sitemap_progress_start');
                    nodeProgressStart.innerHTML = response.data.PROGRESSBAR;
                    this.exportCHPU(response.data.OFFSET, 100, newFile, count, response.data.TOTAL_COUNT);
                } else {
                    const nodeProgress = BX('sitemap_progress');
                    nodeProgress.innerHTML = response.data.PROGRESSBAR;
                    let link = document.createElement('a');
                    link.href = response.data.PATH;
                    link.download = response.data.NAME;
                    link.click();
                    deleteFile();
                }
            }, error => {
                console.error(error);
            });
        }

        function deleteFile(){
            const sheetName = 'seometa_chpu';
            const deleteFile = BX.ajax.runAction('sotbit:seometa.excelExportImport.deleteFile', {
                data: {sheetName}
            }).then(response => {
            }, error => {
                console.error(error);
            });
        }
    </script>

    <div id="seochpu_export" style="display: none;">
        <div id="sitemap_progress">
            <?=SitemapRuntime::showProgress(Loc::getMessage('SEO_META_CHPU_RUN_INIT'), Loc::getMessage('SEO_META_CHPU_RUN_TITLE'), 0)?>
        </div>
        <div id="sitemap_progress_start" style="display: none">
            <?=SitemapRuntime::showProgress(Loc::getMessage('SEO_META_CHPU_RUN_INIT'), Loc::getMessage('SEO_META_CHPU_RUN_TITLE'), 0)?>
        </div>
    </div>
<?
if (CCSeoMeta::ReturnDemo() == 2) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}

$lAdmin->DisplayFilter($FilterArr);
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>