<?php

namespace Sotbit\Seometa\Orm;

use Bitrix\Main\ORM;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class CategoryTagsTable extends \DataManagerEx_SeoMeta
{
    public static function getTableName()
    {
        return 'b_sotbit_seometa_category_tags';
    }

    public static function getMap()
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new ORM\Fields\TextField('NAME', []),
            new ORM\Fields\BooleanField('ACTIVE', [
                'values' => ['N', 'Y'],
            ]),
            new ORM\Fields\IntegerField('SORT', [
                'required' => true,
            ]),
            (new ORM\Fields\Relations\OneToMany('CONDITIONS_BY_SECTIONS', ConditionTable::class, 'CATEGORY_TAGS_SECTION'))->configureJoinType('left'),
            (new ORM\Fields\Relations\OneToMany('CONDITIONS_BY_DETAIL', ConditionTable::class, 'CATEGORY_TAGS_DETAIL'))->configureJoinType('left'),
        ];
    }

    public static function fillCategoryTags($Conditions, $tag, bool|array $tagsCategory, string $type = 'TAGS_SECTION')
    {
        $tag[$type . '_ID'] = $Conditions[$type . '_ID'] ?: $tagsCategory['ID'];
        $tag[$type . '_NAME'] = $Conditions[$type . '_NAME'] ?: $tagsCategory['NAME'];
        $tag[$type . '_SORT'] = $Conditions[$type . '_SORT'] ?: $tagsCategory['SORT'];
        return $tag;
    }
}