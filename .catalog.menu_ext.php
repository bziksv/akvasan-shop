<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

if (CModule::IncludeModule('iblock')) {

    if (defined("BX_COMP_MANAGED_CACHE")) {
        $GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_5");
    }

    $aMenuLinksExt = $APPLICATION->IncludeComponent("custom:menu.sections", "", array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "",
        "SECTION_PAGE_URL" => "",
        "DETAIL_PAGE_URL" => "",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "5",
        "DEPTH_LEVEL" => "3",
        "CACHE_TYPE" => "N",
    ), false, Array('HIDE_ICONS' => 'Y'));

    if (defined("BX_COMP_MANAGED_CACHE")) {
        $GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_new");
    }
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
