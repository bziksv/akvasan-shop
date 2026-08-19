<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$parnterPrefix = "CZEBRA.FORM.";

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arProperty_LNSF = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "F")))
	{
		$arProperty_LNSF[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}


$arComponentParameters = array(
	"GROUPS" => array(
		"FIELDS" => array(
			"NAME" => GetMessage($parnterPrefix."IBLOCK_FIELDS"),
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

		"PROPERTY_CODES" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_LNSF,
		),
		"PROPERTY_CODES_REQUIRED_EMAIL" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PROPERTY_CODES_REQUIRED_EMAIL"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arProperty_LNSF,
		),
		"PROPERTY_CODES_REQUIRED_CHECKBOX" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PROPERTY_CODES_REQUIRED_CHECKBOX"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arProperty_LNSF,
		),
		"PROPERTY_CODES_REQUIRED" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage($parnterPrefix."IBLOCK_PROPERTY_REQUIRED"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arProperty_LNSF,
		),

		"FORM_ID" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."FORM_ID"),
			"TYPE" => "TEXT",
		),
		"VALIDATED" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."VALIDATED"),
			"TYPE" => "CHECKBOX",
		),
		"ACTIVE" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."ACTIVE"),
			"TYPE" => "CHECKBOX",
		),
		"NAME_POST_EVENT" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."NAME_POST_EVENT"),
			"TYPE" => "TEXT",
		),
		"RETURN_URL" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."RETURN_URL"),
			"TYPE" => "TEXT",
		),
		"USER_MESSAGE_ADD" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."USER_MESSAGE_ADD"),
			"TYPE" => "TEXT",
		),
		"MSG_BTN" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."MSG_BTN"),
			"TYPE" => "TEXT",
		),
		"USE_CAPTCHA" => array(
			"PARENT" => "PARAMS",
			"NAME" => GetMessage($parnterPrefix."USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",
		),		
	),
);
