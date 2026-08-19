<?php

namespace Sotbit\Seometa\Orm;

use Bitrix\Main\Localization\Loc;

class SeoAiRequestHistoryTable extends  \DataManagerEx_SeoMeta
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_sotbit_seometa_ai_log';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
            'MODEL' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
            'REQUEST' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
            'RESPONSE' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
            'DATE_CREATE' => array(
                'data_type' => 'date',
                'required' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
            'USER' => array(
                'data_type' => 'date',
                'required' => true,
                'title' => Loc::getMessage('SEOMETA_SECTION_CHPU_ENTITY_ID_FIELD'),
            ),
        );
    }
}