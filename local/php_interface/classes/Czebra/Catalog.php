<?php
namespace Czebra;

use Bitrix\Main\Loader;

class Catalog
{
    public function deacivedElements()
    {
        if (!Loader::includeModule("iblock")) {
            return false;
        }

        if (!Loader::includeModule("catalog")) {
            return false;
        }

        $arSelect = array("ID", "IBLOCK_ID", "NAME");
        $arFilter = array(
            "IBLOCK_ID" => '5',
            "ACTIVE" => "Y",
            "<CATALOG_PRICE_1" => "10",
        );
        $dbElement = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement->GetNext()) {
            $el = new \CIBlockElement;
            $arLoadProductArray = Array("ACTIVE" => "N");
            $el->Update($arElement['ID'], $arLoadProductArray);
        }

        return '\Czebra\Catalog::deacivedElements();';
    }
}
