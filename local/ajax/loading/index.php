<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use \Czebra\BFS\AjaxLoading;
use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();
$arParams = AjaxLoading::getDecryptArray($request["arParams"]);

$arParams["AJAX"] = "Y";
if (strlen(trim($_REQUEST["arFilter"])) > 0 && strlen($arParams["FILTER_NAME"]) > 0) {
    GLOBAL ${$arParams["FILTER_NAME"]};
    ${$arParams["FILTER_NAME"]} = AjaxLoading::getDecryptArray($request["arFilter"]);
    $arParams["ELEMENT_COUNT"] = $arParams["PAGE_ELEMENT_COUNT"];
    $arParams["PAGER_TEMPLATE"] = "full";
}

$APPLICATION->IncludeComponent(
    $arParams["COMPONENT_NAME"], 
    $arParams["TEMPLATE_NAME"], 
    $arParams
);
