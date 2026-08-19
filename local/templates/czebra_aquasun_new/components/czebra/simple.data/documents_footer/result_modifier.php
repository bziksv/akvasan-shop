<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

foreach($arResult["ITEMS"] as $key => $arItem){
    $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["FILE"]["VALUE"] = CFile::GetPath($arItem["DISPLAY_PROPERTIES"]["FILE"]["VALUE"]);
}