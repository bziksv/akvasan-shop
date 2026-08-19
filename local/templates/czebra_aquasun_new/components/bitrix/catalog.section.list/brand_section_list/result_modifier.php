<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult['SECTIONS'] as $key => $arSection){
	if(in_array($arSection["ID"], $arParams["ID_BRAND"])){
		$code = explode("/", $arSection["SECTION_PAGE_URL"]);
		if($code[3]){
			$arResult["SECTIONS"][$key]["SECTION_PAGE_URL"] = "/".$code[1]."/".$code[2]."/".$code[3]."/filter/brend_16-is-".$arParams["XML_ID_BRAND"]."/apply/";
		}
		else {
			$arResult["SECTIONS"][$key]["SECTION_PAGE_URL"] = "/".$code[1]."/".$code[2]."/filter/brend_16-is-".$arParams["XML_ID_BRAND"]."/apply/";
		}
	}
	else {
		unset($arResult["SECTIONS"][$key]);
	}
}

