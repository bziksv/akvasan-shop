<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
	die();
$parnterName = "czebra";
$parnterPrefix = "CZEBRA.FORM.";

$arComponentDescription = array(
	"NAME" => GetMessage($parnterPrefix."COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage($parnterPrefix."COMPONENT_DESCRIPTION"),
	"ICON" => "",
	"PATH" => array(
		"ID" => $parnterName,
		"NAME" => GetMessage($parnterPrefix."DEVELOPER_DESC"),
	),
);
unset($parnterName,$parnterPrefix);
