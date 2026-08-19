<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\Encoding;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

Loc::loadMessages(__FILE__);

global $APPLICATION, $USER;
const MODULE_NAME = 'sotbit.seometa';

$POST_RIGHT = $APPLICATION->GetGroupRight(MODULE_NAME);
if ($POST_RIGHT < "R") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::includeModule(MODULE_NAME)) {
    CAdminMessage::ShowMessage(Loc::getMessage('SEO_META_CT_LIST_INCLUDE'));
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return false;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle(Loc::getMessage('SEO_META_CT_LIST_TITLE'));

\CCSeoMeta::checkDemo();

$context = Context::getCurrent();
$request = $context->getRequest();

$sTableID = "b_sotbit_seometa_category_tags";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminUiList($sTableID, $oSort);

if ($lAdmin->EditAction()) {
    foreach ($request->get('FIELDS') as $ID => $field) {
        if (!$lAdmin->IsUpdated($ID)) {
            continue;
        }

        if ($ID > 0) {
            if (LANG_CHARSET === 'windows-1251') {
                $field = Encoding::convertEncoding($field, "utf-8", LANG_CHARSET);
            }
            $result = \Sotbit\Seometa\Orm\CategoryTagsTable::update($ID, $field);

            if (!$result->isSuccess()) {
                $lAdmin->AddGroupError(Loc::getMessage("SEO_META_CT_LIST_SAVE_ERROR"), $ID);
            }
        } else {
            $lAdmin->AddGroupError(Loc::getMessage("SEO_META_CT_LIST_SAVE_ERROR"), $ID);
        }
    }
}

if ($IDs = $lAdmin->GroupAction()) {
    sort($IDs);
    foreach ($IDs as $ID) {
        $action = $_REQUEST['action'];
        if ((int)$ID === 1 && ($action === 'delete' || $action === 'deactivate')) {
            $lAdmin->AddGroupError(Loc::getMessage("SEO_META_CT_LIST_" . strtoupper($action) . "_ERROR_WITH_DEFAULT_CAT"), $ID);
            break;
        }

        switch ($action) {
            case 'delete':
                $result = \Sotbit\Seometa\Orm\CategoryTagsTable::delete(intval($ID));
                if (!$result->isSuccess()) {
                    $lAdmin->AddGroupError(Loc::getMessage("SEO_META_CT_LIST_DELETE_ERROR"), $ID);
                }
                break;
            case "activate":
            case "deactivate":
                $arFields["ACTIVE"] = $action == "activate" ? "Y" : "N";
                $result = \Sotbit\Seometa\Orm\CategoryTagsTable::update($ID, ['ACTIVE' => $arFields["ACTIVE"]]);
                if (!$result->isSuccess()) {
                    $lAdmin->AddGroupError(Loc::getMessage("SEO_META_CT_LIST_SAVE_ERROR"), $ID);
                }
                break;
        }
    }
}

$arHeaders = [
    [
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ],
    [
        "id" => "DEFAULT",
        "content" => Loc::getMessage("SEO_META_CT_LIST_DEFAULT"),
        "default" => true,
    ],
    [
        "id" => "NAME",
        "content" => Loc::getMessage("SEO_META_CT_LIST_NAME"),
        "sort" => "NAME",
        "default" => true,
    ],
    [
        "id" => "ACTIVE",
        "content" => Loc::getMessage("SEO_META_CT_LIST_ACTIVE"),
        "sort" => "ACTIVE",
        "default" => true,
    ],
    [
        "id" => "SORT",
        "content" => Loc::getMessage("SEO_META_CT_LIST_SORT"),
        "sort" => "SORT",
        "align" => "right",
        "default" => true,
    ],
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
        "id" => "SORT",
        "name" => Loc::getMessage("SEO_META_CT_LIST_SORT"),
        "type" => "number",
        "default" => true
    ],
    [
        "id" => "NAME",
        "name" => Loc::getMessage("SEO_META_CT_LIST_FILTER_NAME"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "ACTIVE",
        "name" => Loc::getMessage("SEO_META_CT_LIST_ACTIVE"),
        "type" => "list",
        "items" => array(
            "Y" => Loc::getMessage("IBLOCK_YES"),
            "N" => Loc::getMessage("IBLOCK_NO")
        ),
        "filterable" => "",
        "default" => true
    ],
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

$catTags = \Sotbit\Seometa\Orm\CategoryTagsTable::query()
    ->addSelect('*')
    ->setOrder($setOrder)
    ->setFilter($arFilter)
    ->fetchAll();

$rsResult = new \CDBResult;
$rsResult->InitFromArray($catTags);
unset($catTags);

$rsData = new CAdminUiResult($rsResult, $sTableID);

if ($rsData->arResult) {
    $rsData->NavStart();
}
$lAdmin->AddHeaders($arHeaders);
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/sotbit.seometa_category_tags_list.php'));

while ($arRes = $rsData->NavNext(false)) {
    $row = $lAdmin->AddRow($arRes['ID'], $arRes, 'sotbit.seometa_category_tags_edit.php?lang=' . LANG . '&ID=' . $arRes['ID'], Loc::getMessage("IBLIST_A_LIST"));
    $row->AddViewField("ID", '<a href="/bitrix/admin/sotbit.seometa_category_tags_edit.php?lang=' . LANG . '&ID=' . $arRes['ID'] . '">' . $arRes['ID'] . '</a>');
    $row->AddInputField("NAME");
    $row->AddInputField("SORT");
    $row->AddCheckField("ACTIVE");

    if((int)$arRes['ID'] === 1) {
        $row->AddViewField('DEFAULT', Loc::getMessage('IBLOCK_YES'));
    } else {
        $row->AddViewField("DEFAULT", Loc::getMessage('IBLOCK_NO'));
    }

    $arActions = [];

    $arActions[] = [
        "ICON" => "edit",
        "DEFAULT" => true,
        "TEXT" => Loc::getMessage("SEO_META_CT_LIST_EDIT"),
        "ACTION" => $lAdmin->ActionRedirect("sotbit.seometa_category_tags_edit.php?ID=" . $arRes['ID'])
    ];

    if ($POST_RIGHT >= "W" && (int)$arRes['ID'] !== 1) {
        $arActions[] = [
            "ICON" => "delete",
            "TEXT" => Loc::getMessage("SEO_META_CT_LIST_DELETE"),
            "ACTION" => "if(confirm('" . Loc::getMessage('SEO_META_CT_LIST_DELETE_CONFIRM') . "')) " . $lAdmin->ActionDoGroup($arRes['ID'], "delete")
        ];
    }

    $row->AddActions($arActions);
}

if ($POST_RIGHT === "W") {
    $aContext = [
        [
            "TEXT" => Loc::getMessage("SEO_META_CT_LIST_ADD"),
            "TITLE" => Loc::getMessage("SEO_META_CT_LIST_ADD"),
            "ICON" => "btn_new",
            "DISABLED" => 'Y',
            "LINK" => 'sotbit.seometa_category_tags_edit.php?lang=' . LANGUAGE_ID,
        ],
    ];

    $lAdmin->AddAdminContextMenu($aContext);
}

$lAdmin->AddGroupActionTable([
    "delete" => Loc::getMessage("SEO_META_CT_LIST_DELETE"),
    "edit" => Loc::getMessage("SEO_META_CT_LIST_EDIT"),
    "activate" => Loc::getMessage("SEO_META_CT_LIST_ACTIVATE"),
    "deactivate" => Loc::getMessage("SEO_META_CT_LIST_DEACTIVATE"),
    "for_all" => true
]);

$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList(); ?>
    <script>
        BX.ready(() => {
            const checkBoxNumbOne = document.querySelector('#checkbox_b_sotbit_seometa_category_tags_1');
            const span = checkBoxNumbOne.closest('.main-grid-cell-content');
            const removeBtn = document.getElementById('grid_remove_button');

            if (span) {
                span.addEventListener('click', function () {
                    const checkBoxNumbOne = document.querySelector('#checkbox_b_sotbit_seometa_category_tags_1');
                    if (!checkBoxNumbOne.checked) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'table-cell';
                    }
                })
            }
        });
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>