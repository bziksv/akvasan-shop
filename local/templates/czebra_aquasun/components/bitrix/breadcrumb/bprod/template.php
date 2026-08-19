<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (empty($arResult)) {
    return "";
}

$strReturn = "".print_r($arParams, true);
$itemSize = count($arResult);

for ($index = 0; $index < $itemSize - 1; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if ($index == 0) {
        $strReturn .= '<div class="active-menu col-xs-12 hidden-lg hidden-md"><img src="' . SITE_TEMPLATE_PATH . '/front/images/blue-arrow.png" class="blue-arrow"><img src="' . SITE_TEMPLATE_PATH . '/front/images/grey-arrow.png" class="grey-arrow">';
    } else {
        $strReturn .= '<div class="active-drop-menu col-xs-12 hidden-lg hidden-md"><img src="' . SITE_TEMPLATE_PATH . '/front/images/white-arrow.png" class="white-arrow">';
    }

    $strReturn .= '<img src="' . SITE_TEMPLATE_PATH . '/front/images/expgr.png"><a href="' . $arResult[$index]["LINK"] . '">' . $title . '</a></div>';
}

$strReturn .= '<div class="breadcrumbs col-lg-12 col-md-12 col-xs-12">';

for ($index = 0; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    $nextRef = ($index < $itemSize - 2 && $arResult[$index + 1]["LINK"] <> "" ? ' itemref="bx_breadcrumb_' . ($index + 1) . '"' : '');
    $child = ($index > 0 ? ' itemprop="child"' : '');

    if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
        $strReturn .= '
        <div class="breadcrumb-item" id="bx_breadcrumb_' . $index . '" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"' . $child . $nextRef . '>
            <a href="' . $arResult[$index]["LINK"] . '" title="' . $title . '" itemprop="url">' . $title . '</a><span itemprop="title"></span>
        </div><span class="delimiter">:</span>';
    } else {
        $strReturn .= '<div class="breadcrumb-item"><span>' . $title . '</span></div>';
    }
}
$strReturn .= '</div>';


return $strReturn;
