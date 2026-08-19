<?php

namespace Sotbit\Seometa\Helper\AdminSection;


use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Sotbit\Seometa\Orm\SeometaUrlTable;

class Tools
{
    public static function getPropertyChpuValue(?string $property, $iblockId): string
    {
        if (!$property) {
            return '';
        }

        $props = unserialize($property, ['allowed_classes' => false]);
        $pr = '';
        if (is_array($props)) {
            foreach ($props as $code => $value) {
                $lastCode = '';
                if ($code == 'FILTER') {
                    $lastCode = $code;
                    $keys = array_keys($value);
                    $code = $keys[0];
                    unset($keys);
                }

                $name = \CIBlockProperty::GetByID($code)->fetch();
                $glue = ', ';
                if (!$name) {
                    $priceItem = \CIBlockPriceTools::GetCatalogPrices($iblockId, [$code])[$code];
                    $name['NAME'] = $priceItem['TITLE'];
                    $glue = ' - ';
                }

                if (empty($name['NAME'])) {
                    $pr .= $code . ' - ' . implode(', ', $value) . '; ';
                } elseif ($lastCode == 'FILTER') {
                    $keys = array_keys(current($value));
                    $pr .= 'FILTER_' . $name['NAME'] . ' - '
                        . (in_array('FROM', $keys) ? 'FROM' : $keys[0])
                        . '-' . implode(', ', current($value)) . '; ';
                } else {
                    $pr .= $name['NAME'] . ' - ' . implode($glue, $value) . '; ';
                }
            }
        }

        return $pr;
    }

    public static function getPropertyChpuArray(string $property, $iblockId): array
    {
        $props = unserialize($property, ['allowed_classes' => false]);
        $result = [];
        if (is_array($props)) {
            foreach ($props as $code => $value) {
                $lastCode = '';
                if ($code == 'FILTER') {
                    $lastCode = $code;
                    $keys = array_keys($value);
                    $code = $keys[0];
                    unset($keys);
                }

                $name = \CIBlockProperty::GetByID($code)->fetch();

                if (!$name) {
                    $priceItem = \CIBlockPriceTools::GetCatalogPrices($iblockId, [$code])[$code];
                    $name['NAME'] = $priceItem['TITLE'];
                    $glue = ' - ';
                }
                if (empty($name['NAME'])) {
                    $result[$code] = [
                        'NAME' => $code,
                        'VALUE' => $value
                    ];
                } elseif ($lastCode == 'FILTER') {
                    $result[$code] = [
                        'NAME' => 'FILTER_' . $name['NAME'],
                        'VALUE' => $value
                    ];
                } else {
                    $result[$code] = [
                        'NAME' => $name['NAME'],
                        'VALUE' => $value
                    ];
                }
            }
        }

        return $result;
    }

    public static function getIblockChpuList(array $categories): array
    {
        $iblockListDb = SeometaUrlTable::query()
            ->addSelect('iblock_id')
            ->addSelect('IBLOCK.NAME', 'IBLOCK_NAME')
            ->registerRuntimeField(
                new \Bitrix\Main\Entity\ReferenceField(
                    'IBLOCK',
                    \Bitrix\Iblock\IblockTable::class,
                    ['=this.iblock_id' => 'ref.ID'],
                    ['join_type' => 'INNER']
                ),
            )
            ->setDistinct()
            ->whereIn('CATEGORY_ID', $categories)
            ->exec();

        while ($item = $iblockListDb->fetch()) {
            $iblockList[$item['iblock_id']] = "[{$item['iblock_id']}] {$item['IBLOCK_NAME']}";
        }

        return $iblockList ?? [];
    }

    public static function getSectionsChpuList(array $categories): array
    {
        $sectionListDb = SeometaUrlTable::query()
            ->addSelect('section_id')
            ->addSelect('SECTION.NAME', 'SECTION_NAME')
            ->registerRuntimeField(
                new \Bitrix\Main\Entity\ReferenceField(
                    'SECTION',
                    \Bitrix\Iblock\SectionTable::class,
                    ['=this.section_id' => 'ref.ID'],
                    ['join_type' => 'LEFT']
                ),
            )
            ->setDistinct()
            ->whereIn('CATEGORY_ID', $categories)
            ->exec();


        while ($item = $sectionListDb->fetch()) {
            if ((int)$item['section_id'] === 0) {
                $sectionList['top'] = Loc::getMessage('IBLOCK_SECTION_TOP');
                continue;
            }

            $sectionList[$item['section_id']] = "[{$item['section_id']}] {$item['SECTION_NAME']}";
        }

        return $sectionList ?? [];
    }

    public static function getSitesChpuList(array $categories): array
    {
        $siteListDb = SeometaUrlTable::query()
            ->addSelect('SITE_ID')
            ->addSelect('SITE.NAME', 'SITE_NAME')
            ->addOrder('SITE.SORT')
            ->registerRuntimeField(
                new \Bitrix\Main\Entity\ReferenceField(
                    'SITE',
                    SiteTable::class,
                    ['=this.SITE_ID' => 'ref.ID'],
                    ['join_type' => 'LEFT']
                ),
            )
            ->setDistinct()
            ->whereIn('CATEGORY_ID', $categories)
            ->whereNotNull('SITE_ID')
            ->exec();


        while ($item = $siteListDb->fetch()) {
            $result[$item['SITE_ID']] = "[{$item['SITE_ID']}] {$item['SITE_NAME']}";
        }

        return $result ?? [];
    }

    public static function getIblockSectionListName(array $arId = [])
    {
        $arSections = array_column(SectionTable::query()
            ->setSelect(['ID', 'NAME'])
            ->whereIn('ID', $arId)
            ->fetchAll(), 'NAME', 'ID');

        $arSections[0] = Loc::getMessage('IBLOCK_SECTION_TOP');

        return $arSections;
    }
}
