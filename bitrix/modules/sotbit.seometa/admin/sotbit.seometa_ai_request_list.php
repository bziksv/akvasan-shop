<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

Loc::loadMessages(__FILE__);

global $APPLICATION, $USER;
const MODULE_NAME = 'sotbit.seometa';

$POST_RIGHT = $APPLICATION->GetGroupRight(MODULE_NAME);
if ($POST_RIGHT < "R") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::includeModule(MODULE_NAME)) {
    CAdminMessage::ShowMessage(Loc::getMessage('SEO_META_AI_LIST_INCLUDE'));
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return false;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle(Loc::getMessage('SEO_META_AI_LIST_TITLE'));

if (CCSeoMeta::ReturnDemo() == 2) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_AI_LIST_DEMO") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?php
}
if (CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_AI_LIST_DEMO_END") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

$context = Context::getCurrent();
$request = $context->getRequest();

$sTableID = "b_sotbit_seometa_ai_requests";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminUiList($sTableID, $oSort);

$arHeaders = [
    [
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ],
    [
        "id" => "CONDITION_ID",
        "content" => Loc::getMessage("SEO_META_AI_LIST_CONDITION_ID"),
        "sort" => "CONDITION_ID",
        "align" => "right",
        "default" => true,
    ],
    [
        "id" => "SEND_REQUEST",
        "content" => Loc::getMessage("SEO_META_AI_LIST_SEND_REQUEST"),
        "sort" => "SEND_REQUEST",
        "default" => true,
    ],
    [
        "id" => "OUTPUT_REQUEST",
        "content" => Loc::getMessage("SEO_META_AI_LIST_OUTPUT_REQUEST"),
        "sort" => "OUTPUT_REQUEST",
        "default" => true,
    ]
];

$arFilter = [];
$filterFields = [
    [
        "id" => "ID",
        "name" => "ID",
        "type" => "number",
        "default" => true
    ],
    [
        "id" => "CONDITION_ID",
        "name" => Loc::getMessage("SEO_META_AI_LIST_FILTER_CONDITION_ID"),
        "type" => "number",
        "default" => true
    ],
    [
        "id" => "CONDITION.NAME",
        "name" => Loc::getMessage("SEO_META_AI_LIST_FILTER_NAME"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ]
];

$lAdmin->AddFilter($filterFields, $arFilter);

$arIDs = array_column($filterFields, "id");

foreach ($arFilter as $key => $item) {
    $findKey = array_search($key, $arIDs);
    if ($findKey && $filterFields[$findKey]['type'] === 'string') {
        $arFilter[$key] = "%" . $item . "%";
    }
}

$by = $request->get('by');
$order = $request->get('order');
$setOrder = ($by && $order) ? [$by => $order] : ["ID" => "DESC"];


$arRequests = \Sotbit\Seometa\Orm\AiRequestTable::query()
    ->addSelect('*')
    ->addSelect('CONDITION.NAME', 'NAME')
    ->setOrder($setOrder)
    ->setFilter($arFilter)
    ->fetchAll();


$rsResult = new \CDBResult;
$rsResult->InitFromArray($arRequests);
unset($arRequests);

$rsData = new CAdminUiResult($rsResult, $sTableID);

if ($rsData->arResult) {
    $rsData->NavStart();
}
$lAdmin->AddHeaders($arHeaders);
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/sotbit.seometa_ai_request_list.php'));
while ($arRes = $rsData->NavNext(false)) {
    $row = $lAdmin->AddRow($arRes['ID'], $arRes, 'sotbit.seometa_ai_request_list.php?lang=' . LANG . '&ID=' . $arRes['ID'], Loc::getMessage("IBLIST_A_LIST"));
    $row->AddViewField("ID", '<a href="/bitrix/admin/sotbit.seometa_edit.php?lang=' . LANG . '&ID=' . $arRes['CONDITION_ID'] . '">' . $arRes['ID'] . '</a>');
    $row->AddViewField("CONDITION_ID", '<a href="/bitrix/admin/sotbit.seometa_edit.php?lang=' . LANG . '&ID=' . $arRes['CONDITION_ID'] . '">' . $arRes['CONDITION_ID'] . " " . $arRes['NAME'] . '</a>');

    $row->AddViewField("USER_ID", '<a href="/bitrix/admin/user_edit.php?lang=' . LANG . '&ID=' . $arRes['USER_ID'] . '">' . $arRes['USER_ID'] . '</a>');
    $row->AddField("SEND_REQUEST", $arRes['SEND_REQUEST']);
    $row->AddField("OUTPUT_REQUEST", $arRes['OUTPUT_REQUEST']);
}

$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList(['ACTION_PANEL' => false]);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>