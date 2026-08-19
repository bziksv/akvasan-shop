<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!CModule::IncludeModule("iblock"))
    return;

$arTemplateParameters = array(
    'SORT_ORDER_TAGS' => array(
        "PARENT" => "BASE",
        'NAME' => GetMessage('SM_SORT_ORDER'),
        'TYPE' => "LIST",
        "VALUES" => array('asc' => GetMessage("SM_SORT_ORDER_ASC"), 'desc' => GetMessage("SM_SORT_ORDER_DESC")),
    ),
);
?>