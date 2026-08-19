<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['GRID']['ROWS'] as &$item) {

    $item["PRICE_FORMATED_RUB"] = str_replace("руб.","<span class='rubl'>i</span>", $item["PRICE_FORMATED"]);
    $item["SUM_FULL_PRICE_FORMATED_RUB"] = str_replace("руб.","<span class='rubl'>i</span>", $item["SUM"]);

    if (strlen($item["DETAIL_PICTURE_SRC"]) == 0) {
        $item["DETAIL_PICTURE_SRC"] = "/local/templates/czebra_titul_project/front/img/no-photo.png";
    }
}

$arResult["allSum_FORMATED"] = str_replace("руб.","<span class='rubl'>i</span>", $arResult["allSum_FORMATED"]);