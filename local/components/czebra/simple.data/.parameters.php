<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) 
	die();

if (!CModule::IncludeModule("iblock"))
	return;

$parnterPrefix = "CZEBRA.SIMPLEDATA.";

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$rsIBlock = CIBlock::GetList(
	Array("sort" => "asc"), 
	Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], 
	"ACTIVE"=>"Y")
);
while ($arr = $rsIBlock->Fetch()) {
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arProperty_LNSF = array();
$rsProp = CIBlockProperty::GetList(
	Array("sort"=>"asc", "name"=>"asc"), 
	Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"])
);
while ($arr=$rsProp->Fetch()) {
	$arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "F"))) {
		$arProperty_LNSF[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arSorts = array(
	"ASC" => GetMessage($parnterPrefix."T_IBLOCK_DESC_ASC"), 
	"DESC" => GetMessage($parnterPrefix."T_IBLOCK_DESC_DESC")
);
$arSortFields = array(
	"ID" => GetMessage($parnterPrefix."T_IBLOCK_DESC_FID"),
	"NAME" => GetMessage($parnterPrefix."T_IBLOCK_DESC_FNAME"),
	"ACTIVE_FROM" => GetMessage($parnterPrefix."T_IBLOCK_DESC_FACT"),
	"SORT" => GetMessage($parnterPrefix."T_IBLOCK_DESC_FSORT"),
	"TIMESTAMP_X" => GetMessage($parnterPrefix."T_IBLOCK_DESC_FTSAMP")
);

$arComponentParameters = array(
	"GROUPS" => array(
		"FIELDS" => array(
			"NAME" => GetMessage($parnterPrefix."IBLOCK_FIELDS"),
			"SORT" => "400",
		),
		"SORT" => array(
			"NAME" => GetMessage($parnterPrefix."SORT"),
			"SORT" => "400",
		),
		"PARAMS" => array(
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PARAMS"),
			"SORT" => "450"
		),
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),

		"FIELD_CODE" => 
		  	CIBlockParameters::GetFieldCode(
				GetMessage($parnterPrefix."IBLOCK_FIELD"), 
				"FIELDS"
			),
		"PROPERTY_CODE" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_LNSF,
			"ADDITIONAL_VALUES" => "Y",
		),

		"SORT_BY1" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage($parnterPrefix."SORT_BY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER1" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage($parnterPrefix."SORT_ORDER1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_BY2" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage($parnterPrefix."SORT_BY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ID",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER2" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage($parnterPrefix."SORT_ORDER2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),

		"FILTER_NAME" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."FILTER_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"COUNT" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."COUNT"),
			"TYPE" => "TEXT",
		),
		"SHOW_ALL_PROPERTIES" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."SHOW_ALL_PROPERTIES"),
			"TYPE" => "CHECKBOX",
		),

		

		"CACHE_TIME" => array(
			"DEFAULT"=>36000000
		),
	),
);
