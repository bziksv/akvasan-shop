<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//Правильная сортировка для результатов поиска
if (isset($arParams["SORT_SEARCH"])) {
    $arNewItemsList = array();
    foreach ($arParams["SORT_SEARCH"] as $sk => $value) {
        foreach ($arResult['ITEMS'] as $key => $arItem) {
            if ($value == $arItem["ID"]) {
                $arNewItemsList[$key] = $arItem;
                unset($arResult['ITEMS'][$key]);
            }
        }
    }
    $arResult['ITEMS'] = $arNewItemsList;
}