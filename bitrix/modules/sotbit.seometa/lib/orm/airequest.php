<?php

namespace Sotbit\Seometa\Orm;

use Bitrix\Main\ORM;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class AiRequestTable extends \DataManagerEx_SeoMeta
{
    public static function getTableName()
    {
        return 'b_sotbit_seometa_ai_requests';
    }

    public static function getMap()
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new ORM\Fields\IntegerField('CONDITION_ID', [
                'required' => true,
            ]),
            new ORM\Fields\TextField('SEND_REQUEST', [

            ]),
            new ORM\Fields\TextField('OUTPUT_REQUEST', [

            ]),
            new Reference(
                'CONDITION',
                ConditionTable::class,
                Join::on('this.CONDITION_ID', 'ref.ID'),
            ),
        ];
    }
}