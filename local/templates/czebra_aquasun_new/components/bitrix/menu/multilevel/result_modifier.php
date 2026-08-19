<?php
$IBLOCK_ID = 5;
$pages   = $APPLICATION -> GetCurDir();
$pages   = explode('/', $pages);

$dbRes = CIBlockSection::GetList(array(), ['IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $pages[2]], false, array("ID", "UF_DELETE_INDEX"));
if ($arCurSection = $dbRes->Fetch())
    $arResult['PROPERTY']['DELETE_INDEX'] = $arCurSection['UF_DELETE_INDEX'];

if(!$arResult['PROPERTY']['DELETE_INDEX']){
	$dbRes = CIBlockSection::GetList(array(), ['IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $pages[3]], false, array("ID", "UF_DELETE_INDEX"));
	if ($arCurSection = $dbRes->Fetch())
		$arResult['PROPERTY']['DELETE_INDEX'] = $arCurSection['UF_DELETE_INDEX'];
}
	
if(!$arResult['PROPERTY']['DELETE_INDEX']){

	$res = CIBlockElement::GetList(Array(), ["IBLOCK_ID" => $IBLOCK_ID, "CODE" => $pages[3]], false, false, ["ID", "IBLOCK_ID", "PROPERTY_*"]);
	if($ob = $res->GetNextElement()){ 
	 $arProps = $ob->GetProperties();
	 $arResult['PROPERTY']['DELETE_INDEX'] = $arProps['DELETE_INDEX']['VALUE'];
	}
	
}