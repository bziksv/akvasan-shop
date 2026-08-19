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

if($arParams["BRAND"]){
    $existBrandSEO = false;
    $resBrandSEO = CIBlockElement::GetList([], ["IBLOCK_ID" => "13", "ACTIVE" => "Y", "PROPERTY_LINK" => $APPLICATION->GetCurPage()], false, false, ["PROPERTY_TITLE", "PROPERTY_DESCRIPTION", "PROPERTY_KEYWORDS", "DETAIL_TEXT"]);
    while($arBrandSEO = $resBrandSEO -> GetNext()){
        $arResult["DESCRIPTION"] = $arBrandSEO["DETAIL_TEXT"];
        if($arBrandSEO["PROPERTY_TITLE_VALUE"]){
            $APPLICATION->SetPageProperty("title", $arBrandSEO["PROPERTY_TITLE_VALUE"]);
        }
        if($arBrandSEO["PROPERTY_DESCRIPTION_VALUE"]){
            $APPLICATION->SetPageProperty("description", $arBrandSEO["PROPERTY_DESCRIPTION_VALUE"]["TEXT"]);
        }
        if($arBrandSEO["PROPERTY_KEYWORDS_VALUE"]){
            $APPLICATION->SetPageProperty("keywords", $arBrandSEO["PROPERTY_KEYWORDS_VALUE"]);
        }
        $existBrandSEO = true;
    }
    if(!$existBrandSEO){
        $arResult["DESCRIPTION"] = "";
    }
}


//global $USER;
//if ($USER->IsAdmin()){

	global $arrFilter;

	$cntBlur = $arParams["PAGE_ELEMENT_COUNT"] - count($arResult['ITEMS']);

	$arSelect = Array("ID", "NAME", "UF_COUNT_PAGE");
	$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>$arResult["CODE"]);

	$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		if($arFields["UF_COUNT_PAGE"]){
			$cntBlur = $arFields["UF_COUNT_PAGE"] - count($arResult['ITEMS']);
		}
	}

	if($cntBlur > 0 && strlen($arParams["SECTION_CODE"])){		

		$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_STRANA");
		$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"N");

		if($arResult["CODE"]){
			$arFilter["SECTION_CODE"] = $arResult["CODE"];
		}

		if($_REQUEST["q"]){
			$arFilter["NAME"] = '%'.$_REQUEST["q"].'%';
		}

		if($arrFilter["=PROPERTY_1511"]){

			$arFilter["PROPERTY_1511"] = $arrFilter["=PROPERTY_1511"];
		}

		$res = CIBlockElement::GetList(Array($arParams["ELEMENT_SORT_FIELD"]=>$arParams["ELEMENT_SORT_ORDER"]), $arFilter, false, Array("nPageSize"=>$cntBlur), $arSelect);
		while($ob = $res->GetNextElement()){ 
			$arFields = $ob->GetFields(); 
			$arResultItem=[];
			$arResultItem["PREVIEW_PICTURE"]["ID"] = $arFields["PREVIEW_PICTURE"];
			$arResultItem["PREVIEW_PICTURE"]["SRC"] = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
			$arResultItem["NAME"] = $arFields["NAME"];
			$arResultItem["PROPERTIES"]["STRANA"]["VALUE"] = $arFields["PROPERTY_STRANA_VALUE"];
			$arResultItem["NOTE"] = "не поставляется";
			$arResultItem["CLASS"] = "blur";

			$arResult['ITEMS'][] = $arResultItem;
		}
	}
	//}
