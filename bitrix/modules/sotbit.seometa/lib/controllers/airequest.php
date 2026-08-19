<?php

namespace Sotbit\Seometa\Controllers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sotbit\Seometa\AI\EndPoints\EndpointsContainer;
use Sotbit\Seometa\Filter\SmartFilter;
use Sotbit\Seometa\Helper\AdminSection\Tools;
use Sotbit\Seometa\Orm\AiRequestTable;
use Sotbit\Seometa\Orm\ConditionTable;
use Sotbit\Seometa\Orm\SeometaUrlTable;
use Sotbit\Seometa\Section\SectionCollection;

class AiRequest extends Controller
{
    private static array $priceCheck = [
        'MIN' => ['MinPrice', 'CondIBMinPrice'],
        'MAX' => ['MaxPrice', 'CondIBMaxPrice'],
        'MIN_FILTER' => ['MinFilterPrice', 'CondIBMinFilterPrice'],
        'MAX_FILTER' => ['MaxFilterPrice', 'CondIBMaxFilterPrice'],
    ];

    private static array $metaToResponse = [
        'TITLE' => 'Title',
        'KEYWORDS' => 'Keywords',
        'DESCRIPTION' => 'Description',
        'H1' => 'H1',
        'UPPER_DESCRIPTION' => 'UpperDescription',
        'LOWER_DESCRIPTION' => 'LowerDescription',
        'ADDITIONAL_DESCRIPTION' => 'AdditionalDescription',
    ];

    public function configureActions()
    {
        $configureActions = parent::configureActions();

        $configureActions['sendRequest'] = [
            'class' => self::class,
        ];
        $configureActions['generateMeta'] = [
            'class' => self::class,
        ];
        $configureActions['sendRequestWithMessage'] = [
            'class' => self::class,
        ];

        return $configureActions;
    }

    public function sendRequestAction($id, $message)
    {
        $container = new EndpointsContainer();
        $mainSettings = $container->getMainSettings();

        try {
            $answer = $container->{$mainSettings['MODEL']}->sendMessage($message, true);
            $answer = trim($answer, "\r\n");

            $aiRequest = AiRequestTable::query()
                ->setFilter(['CONDITION_ID' => $id])
                ->addSelect('*')
                ->fetch();

            if ($aiRequest) {
                AiRequestTable::update($aiRequest['ID'], [
                    'SEND_REQUEST' => $message,
                    'OUTPUT_REQUEST' => $answer
                ]);
            } else {
                AiRequestTable::add([
                    'CONDITION_ID' => $id,
                    'SEND_REQUEST' => $message,
                    'OUTPUT_REQUEST' => $answer
                ]);
            }

            return [
                'ANSWER' => $answer,
            ];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
            return null;
        }
    }

    public function generateMetaAction($id)
    {
        if (!$this->getRequest()->get('checkMeta')) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_CHOOSE_TEMPLATE_FOR_GENERATE')));
            return null;
        }

        $checkMeta = $this->getRequest()->get('checkMeta');

        $condition = ConditionTable::query()->setFilter(['ID' => $id])->addSelect('*')->fetch();

        if (!$condition) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_EXIST_CONDITION')));
            return null;
        }

        if ($sectionsIdsList = unserialize($condition['SECTIONS'])) {
            $sections = SectionCollection::getInstance()->getCollection($sectionsIdsList);

            $categoryName = Loc::getMessage('SEO_META_AI_CATEGORIES_NAME');
            $changeName = Loc::getMessage('SEO_META_AI_CATEGORIES_CHANGE_NAME');
            foreach ($sections as $section) {
                $categoryName .= '"' . $section->NAME . '", ';
                $changeName .= '"' . $section->NAME . '", ';
            }
            $changeName .= Loc::getMessage('SEO_META_AI_CATEGORIES_CHANGE_VALUE');
            $categoryName = trim($categoryName, ', ') . '.';


            if (Loader::includeModule('catalog') && Loader::includeModule('highloadblock')) {
                $iblockInfo = \CCatalogSKU::GetInfoByProductIBlock($condition['INFOBLOCK']);

                $propertyList = [];

                if ($rule = unserialize($condition['RULE'])) {
                    $this->propertyFill($rule['CHILDREN'], $propertyList, $rule['DATA']['All']);
                    $properties = PropertyTable::query()
                        ->setFilter(['ID' => array_keys($propertyList)])
                        ->addSelect('*')
                        ->exec();
                    while ($prop = $properties->fetch()) {
                        if ($propertyList[$prop['ID']]) {
                            $propertyList[$prop['ID']]['NAME'] = $prop['NAME'];
                            $propertyList[$prop['ID']]['CODE'] = $prop['CODE'];
                            if ($iblockInfo['IBLOCK_ID'] === (int)$prop['IBLOCK_ID']) {
                                $propertyList[$prop['ID']]['PROPERTY_VAL'] = 'OfferProperty';
                            } else {
                                $propertyList[$prop['ID']]['PROPERTY_VAL'] = 'ProductProperty';
                            }
                        }

                        if ($propertyList[$prop['ID']] && ($prop['PROPERTY_TYPE'] . ':' . $prop['USER_TYPE'] === 'S:directory')) {
                            $propertyList[$prop['ID']]['TABLE_NAME'] = $prop['USER_TYPE_SETTINGS_LIST']['TABLE_NAME'];
                        } elseif ($propertyList[$prop['ID']] && ($prop['PROPERTY_TYPE'] === 'L')) {
                            $propertyList[$prop['ID']]['LIST'] = true;
                        }
                    }
                }

                foreach ($propertyList as $key => &$property) {
                    if ($property['TABLE_NAME'] && $property['VALUE']) {
                        $compileEntity = HighloadBlockTable::getList(
                            [
                                'select' => ['TABLE_NAME', 'NAME', 'ID'],
                                'filter' => ['=TABLE_NAME' => $property['TABLE_NAME']]
                            ]
                        )->fetch();
                        $entity = HighloadBlockTable::compileEntity($compileEntity);
                        $entityClass = $entity->getDataClass();
                        $res = $entityClass::query()
                            ->setFilter(['ID' => $property['VALUE']])
                            ->addSelect('UF_NAME')
                            ->fetchAll();

                        $property['VALUE'] = '';
                        $countItem = count($res);
                        $i = 1;
                        foreach ($res as $value) {
                            if ($countItem === $i) {
                                $property['VALUE'] .= $value['UF_NAME'];
                            } elseif ($property['COND_VALUE'] === 'AND') {
                                $property['VALUE'] .= $value['UF_NAME'] . Loc::getMessage('SEO_META_AI_AND');
                            } else {
                                $property['VALUE'] .= $value['UF_NAME'] . Loc::getMessage('SEO_META_AI_OR');
                            }
                            $i++;
                        }

                        unset($property['TABLE_NAME']);
                    } elseif ($property['LIST'] && $property['VALUE']) {
                        $enumValue = PropertyEnumerationTable::query()
                            ->setFilter(['ID' => $property['VALUE'], 'PROPERTY_ID' => $key])
                            ->addSelect('VALUE')
                            ->fetchAll();

                        $property['VALUE'] = '';
                        $countItem = count($enumValue);
                        $i = 1;
                        foreach ($enumValue as $value) {
                            if ($countItem === $i) {
                                $property['VALUE'] .= $value['VALUE'];
                            } elseif ($property['COND_VALUE'] === 'AND') {
                                $property['VALUE'] .= $value['VALUE'] . Loc::getMessage('SEO_META_AI_AND');
                            } else {
                                $property['VALUE'] .= $value['VALUE'] . Loc::getMessage('SEO_META_AI_OR');
                            }
                            $i++;
                        }
                        unset($property['LIST']);
                    } elseif (($property['MIN_FILTER'] || $property['MAX_FILTER'])) {
                        $property['VALUE'] = '';
                        if ($property['MIN_FILTER']) {
                            $property['VALUE'] .= Loc::getMessage('SEO_META_AI_MIN_FILTER', ['#VALUE#' => $property['MIN_FILTER']]);
                        }
                        if ($property['MAX_FILTER']) {
                            $property['VALUE'] .= Loc::getMessage('SEO_META_AI_MAX_FILTER', ['#VALUE#' => $property['MAX_FILTER']]);
                        }
                        unset($property['MIN_FILTER'], $property['MAX_FILTER']);
                    } elseif ($property['PRICE']) {
                        $property['VALUE'] = '';

                        if ($property['PRICE']['MIN_FILTER'] || $property['PRICE']['MIN']) {
                            $minFilter = $property['PRICE']['MIN_FILTER'] ?: $property['PRICE']['MIN'];
                            $property['VALUE'] .= Loc::getMessage('SEO_META_AI_MIN_FILTER', ['#VALUE#' => $minFilter]);
                        }
                        if ($property['PRICE']['MAX_FILTER'] || $property['PRICE']['MAX']) {
                            $maxFilter = $property['PRICE']['MAX_FILTER'] ?: $property['PRICE']['MAX'];
                            $property['VALUE'] .= Loc::getMessage('SEO_META_AI_MAX_FILTER', ['#VALUE#' => $maxFilter]);
                        }
                    }
                    unset($property['COND_VALUE']);
                }

                if ($propertyList) {
                    $properties = Loc::getMessage('SEO_META_AI_PROPERTIES_NAME');
                    $prices = Loc::getMessage('SEO_META_AI_PROPERTIES_PRICE');
                    $changeProp = '';

                    foreach ($propertyList as $item) {
                        $text = Loc::getMessage('SEO_META_AI_PROPERTIES_VALUE');
                        if ($item['PRICE']) {
                            $prices .= $item['VALUE'];
                            continue;
                        }

                        $properties .= '"' . $item['NAME'] . '"';
                        if ($item['VALUE']) {
                            $properties .= $text . $item['VALUE'];
                        }

                        $changeProp .= Loc::getMessage('SEO_META_AI_PROPERTIES_CHANGE',
                            [
                                '#VALUE#' => $item['NAME'],
                                '#CODE#' => $item['CODE'],
                                '#PROPERTY_VAL#' => $item['PROPERTY_VAL']
                            ]);

                        $properties .= '; ';
                    }

                    $requiredCondition = '';
                    if ($changeProp || $changeName) {
                        $requiredCondition = Loc::getMessage('SEO_META_AI_PROPERTIES_CHANGE_START');
                    }

                    $properties = trim($properties, '; ');

                    $keywordsForRequest = $this->getRequest()->get('KEYWORDS_FOR_REQUEST');

                    foreach ($checkMeta as $key => $item) {
                        $checkMeta[$key] = Loc::getMessage('SEO_META_AI_' . $key . '_START');
                        if ($keywordsForRequest) {
                            $checkMeta[$key] .= Loc::getMessage('SEO_META_AI_COMMON_MEDIUM', ['#TEXT#' => $keywordsForRequest]);
                        }
                        $checkMeta[$key] .= Loc::getMessage('SEO_META_AI_' . $key . '_FINISH');
                    }

                    if ($prices === Loc::getMessage('SEO_META_AI_PROPERTIES_PRICE')) {
                        $prices = '';
                    } else {
                        $prices .= "\r\n";
                    }

                    $finalMessage = $categoryName . " " . $properties . "\r\n" . $prices . implode("\r\n", $checkMeta) . "\r\n" . $requiredCondition . $changeProp . $changeName;
                    return [
                        'STATUS' => 'COMPLETED',
                        'COMPLETED_MESSAGE' => $finalMessage,
                        'CHECK_META' => json_encode(array_keys($checkMeta)),
                    ];
                } else {
                    $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_HAVE_PROPERTY')));
                    return null;
                }
            } else {
                $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_INCLUDE_MODULES')));
                return null;
            }
        } else {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_HAVE_SECTIONS')));
            return null;
        }
    }

    public function generateChpuMetaAction($id)
    {
        if (!$this->getRequest()->get('checkMeta')) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_CHOOSE_TEMPLATE_FOR_GENERATE')));
            return null;
        }

        $chpu = SeometaUrlTable::getById($id);
        if (!$chpu) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_EXIST_CONDITION')));
            return null;
        }

        $sectionId = $chpu['section_id'];
        if ($sectionId > 0) {
            $section = SectionTable::query()
                ->addSelect('NAME')
                ->where('ID', $sectionId)
                ->fetch();

            if ($section) {
                $categoryName = Loc::getMessage('SEO_META_AI_CATEGORIES_NAME');
                $categoryName .= '"' . $section['NAME'] . '".';
            }
        }

        $propertyList = Tools::getPropertyChpuArray($chpu['PROPERTIES'], $chpu['iblock_id']);
        if (empty($propertyList)) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_HAVE_PROPERTY')));
            return null;
        }

        $properties = Loc::getMessage('SEO_META_AI_PROPERTIES_NAME');
        $prices = Loc::getMessage('SEO_META_AI_PROPERTIES_PRICE');

        foreach ($propertyList as $item) {
            $text = Loc::getMessage('SEO_META_AI_PROPERTIES_VALUE');
            if ($item['PRICE']) {
                $prices .= $item['VALUE'];
                continue;
            }

            $properties .= '"' . $item['NAME'] . '"';
            if ($item['VALUE']) {
                $properties .= $text . implode(', ', $item['VALUE']);
            }

            $properties .= '; ';
        }

        $properties = trim($properties, '; ');

        $keywordsForRequest = $this->getRequest()->get('KEYWORDS_FOR_REQUEST');
        $checkMeta = $this->getRequest()->get('checkMeta');

        foreach ($checkMeta as $key => $item) {
            $checkMeta[$key] = Loc::getMessage('SEO_META_AI_' . $key . '_START');
            if ($keywordsForRequest) {
                $checkMeta[$key] .= Loc::getMessage('SEO_META_AI_COMMON_MEDIUM', ['#TEXT#' => $keywordsForRequest]);
            }
            $checkMeta[$key] .= Loc::getMessage('SEO_META_AI_' . $key . '_FINISH');
        }

        if ($prices === Loc::getMessage('SEO_META_AI_PROPERTIES_PRICE')) {
            $prices = '';
        } else {
            $prices .= "\r\n";
        }

        $finalMessage = ($categoryName ?? '') . " " . $properties . "\r\n" . $prices . implode("\r\n", $checkMeta) . "\r\n" . Loc::getMessage('SEO_META_AI_FINISH_PROMPT');
        return [
            'STATUS' => 'COMPLETED',
            'COMPLETED_MESSAGE' => $finalMessage,
            'CHECK_META' => json_encode(array_keys($checkMeta)),
        ];
    }

    public function sendRequestWithMessageAction($choosingMeta)
    {
        if (!$this->getRequest()->get('finalMessage')) {
            $this->addError(new Error(Loc::getMessage('SEO_META_AI_NOT_FINAL_MESSAGE')));
            return null;
        }

        $container = new \Sotbit\Seometa\AI\EndPoints\EndpointsContainer();
        $model = $container->getMainSettings()['MODEL'];
        try {
            $response = $container->{$model}->sendMessage(str_replace("\r\n", "\r", $this->getRequest()->get('finalMessage')));
            $this->choosingMeta = json_decode($choosingMeta);
            return $this->processingResponse($response);
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
            return null;
        }
    }

    public function cancelAction(): array
    {
        return [
            'STATUS' => 'COMPLETED',
        ];
    }

    public function propertyFill(array $children, array &$propertyList, string $parentCond)
    {
        foreach ($children as $child) {
            $childCond = explode(":", $child['CLASS_ID']);

            if ($child['DATA']['value'] && $childCond[2]) {
                if ($propertyList[$childCond[2]] && $propertyList[$childCond[2]]['VALUE']) {
                    if (!is_array($propertyList[$childCond[2]]['VALUE'])) {
                        $propertyList[$childCond[2]]['VALUE'] = [$propertyList[$childCond[2]]['VALUE']];
                    }
                    $propertyList[$childCond[2]]['VALUE'][] = $child['DATA']['value'];
                } else {
                    $propertyList[$childCond[2]]['VALUE'] = $child['DATA']['value'];
                    $propertyList[$childCond[2]]['COND_VALUE'] = $parentCond;
                }
                if (str_contains($childCond[0], 'MinFilter')) {
                    $propertyList[$childCond[2]]['MIN_FILTER'] = $child['DATA']['value'];
                } elseif (str_contains($childCond[0], 'MaxFilter')) {
                    $propertyList[$childCond[2]]['MAX_FILTER'] = $child['DATA']['value'];
                }
            } elseif ($childCond[2]) {
                $propertyList[$childCond[2]]['VALUE'] = '';
                $propertyList[$childCond[2]]['COND_VALUE'] = $parentCond;

                if (str_contains($childCond[0], 'MinFilter')) {
                    $propertyList[$childCond[2]]['MIN_FILTER'] = $child['DATA']['value'];
                } elseif (str_contains($childCond[0], 'MaxFilter')) {
                    $propertyList[$childCond[2]]['MAX_FILTER'] = $child['DATA']['value'];
                }
            }

            $this->priceFill($propertyList, $childCond, $child);

            if ($child['CHILDREN']) {
                $parentCond = $child['DATA']['All'];
                $this->propertyFill($child['CHILDREN'], $propertyList, $parentCond);
            }
        }

        return $propertyList;
    }

    public function priceFill(array &$propertyList, array $childCond, array $child)
    {
        foreach (self::$priceCheck as $codeValue => $price) {
            if (str_contains($childCond[0], $price[0])) {
                $code = str_replace($price[1], '', $child['CLASS_ID']);
                $propertyList[$code]['PRICE'][$codeValue] = $child['DATA']['value'];
            }
        }
    }

    public function processingResponse($response)
    {
        $result = [
            'STATUS' => 'COMPLETED',
        ];
        foreach ($this->choosingMeta as $item) {
            $needMetaValue = self::$metaToResponse[$item];
            if ($needMetaValue) {
                if ($item === 'TITLE') {
                    preg_match("/(?:\(|^)\s*{$needMetaValue}\s*\)?:?\s*(.*)/i", $response, $matches);
                } else {
                    $mess = Loc::getMessage('SEO_META_AI_' . $item);
                    preg_match("/(?:\(|^)\s*{$mess}|{$needMetaValue}\s*\)?:?\s*(.*)/i", $response, $matches);
                }

                $result[$item] = trim($matches[1]);
            }
        }

        return $result;
    }
}