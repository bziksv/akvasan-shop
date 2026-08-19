<?php
namespace Czebra\BFS;

use Bitrix\Main\Loader;

class Search
{
    public function BeforeIndexHandler($arFields)
    {
        if (
            Loader::includeModule("iblock")
            && $arFields["MODULE_ID"] == 'iblock'
            && intval($arFields["ITEM_ID"]) > 0
        ) {
            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_CML2_ARTICLE");
            $arFilter = Array("ID" => $arFields["ITEM_ID"]);
            $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1), $arSelect);
            if ($arElement = $res->GetNext()) {
                $arFields["BODY"] = $arElement["NAME"] . " " . $arElement["PROPERTY_CML2_ARTICLE_VALUE"] . " " . $arElement["ID"];
            }
        }
        return $arFields;
    }
}
