<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (empty($arResult)) {
    return "";
}

$strReturn = "";
$itemSize = count($arResult);

for ($index = 0; $index < $itemSize-1; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if ($index == 0) {
        $strReturn .= '<div class="active-menu col-xs-12 hidden-lg hidden-md"><img src="' . SITE_TEMPLATE_PATH . '/front/images/blue-arrow.png" class="blue-arrow"><img src="' . SITE_TEMPLATE_PATH . '/front/images/grey-arrow.png" class="grey-arrow">';
    } else {
        $strReturn .= '<div class="active-drop-menu col-xs-12 hidden-lg hidden-md"><img src="' . SITE_TEMPLATE_PATH . '/front/images/white-arrow.png" class="white-arrow">';
    }

    $strReturn .= '<img src="' . SITE_TEMPLATE_PATH . '/front/images/expgr.png"><a href="' . $arResult[$index]["LINK"] . '">' . $title . '</a></div>';
}

return $strReturn;
