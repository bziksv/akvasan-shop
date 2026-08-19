<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
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
    CAdminMessage::ShowMessage(Loc::getMessage('SEO_META_CT_EDIT_INCLUDE'));
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return false;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

\CCSeoMeta::checkDemo();

$context = Context::getCurrent();
$request = $context->getRequest();
$requestValues = $request->getValues();
$ID = $requestValues['ID'];
if($ID > 0) {
    $APPLICATION->SetTitle(Loc::getMessage('SEO_META_CT_EDIT_TITLE', ['#ID#' => $ID]));
    $categoryTag = \Sotbit\Seometa\Orm\CategoryTagsTable::getById($ID)->fetch();
} else {
    $APPLICATION->SetTitle(Loc::getMessage('SEO_META_CT_ADD_TITLE'));
}

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage('SEO_META_CT_EDIT_TAB'),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage('SEO_META_CT_EDIT_TITLE')
    ],
];
$tabControl = new CAdminForm("tabControl", $aTabs);


if ($request->isPost() && ($request->get('save') || $request->get('apply')) && $POST_RIGHT == "W" && check_bitrix_sessid()) {
    $arFields = [
      'NAME' => $requestValues['NAME'],
      'SORT' => $requestValues['SORT'],
      'ACTIVE' => ((int)$ID === 1) ? 'Y' : ($requestValues['ACTIVE'] ?: 'N'),
    ];

    $errors = [];
    if(!is_numeric($arFields['SORT'])) {
        $errors[] = Loc::getMessage('SEO_META_CT_EDIT_SORT_ERR');
    }

    if(!$errors) {
        if($ID > 0) {
            $result = \Sotbit\Seometa\Orm\CategoryTagsTable::update($ID, $arFields);
        } else {
            $result = \Sotbit\Seometa\Orm\CategoryTagsTable::add($arFields);
        }

        if($result->isSuccess()) {
            if ($request->get('apply')) {
                LocalRedirect("/bitrix/admin/sotbit.seometa_category_tags_edit.php?ID=" . $result->getId() . "&mess=ok&lang=" . LANG);
            } else {
                LocalRedirect("/bitrix/admin/sotbit.seometa_category_tags_list.php?lang=" . LANG);
            }
        } else {
            $errors = $result->getErrors();
        }
    }
}

if (!empty($errors) && is_array($errors)) {
    CAdminMessage::ShowMessage([
        "MESSAGE" => implode("\r\n", $errors)
    ]);
}

if ($request->get('mess') === "ok" && $ID > 0 && empty($errors)) {
    CAdminMessage::ShowMessage([
        "MESSAGE" => Loc::getMessage("SEO_META_CT_EDIT_SAVED"),
        "TYPE" => "OK"
    ]);
}

$tabControl->Begin(["FORM_ACTION" => $APPLICATION->GetCurPageParam()]);

$tabControl->BeginNextFormTab();

$tabControl->AddViewField('ID', "ID:", $ID);

$tabControl->BeginCustomField("HID", '');
echo bitrix_sessid_post();
$tabControl->EndCustomField("HID");
$checked = $requestValues['ACTIVE'] === 'Y' || $categoryTag['ACTIVE'] === 'Y';
if((int)$categoryTag['ID'] === 1) {
    $checked = 'Y';
}
$tabControl->AddCheckBoxField('ACTIVE',
    Loc::getMessage('SEO_META_CT_EDIT_ACTIVE'),
    false,
    'Y',
    $checked,
    (int)$categoryTag['ID'] === 1 ? ['disabled'] : '',
);

$tabControl->AddEditField("SORT",
    Loc::getMessage('SEO_META_CT_EDIT_SORT'),
    true,
    [
        "size" => 6,
        "maxlength" => 255
    ],
    htmlspecialcharsbx($requestValues['SORT'] ?: $categoryTag['SORT'] ?: 100)
);

$tabControl->AddEditField("NAME",
    Loc::getMessage('SEO_META_CT_EDIT_NAME'),
    true,
    [],
    htmlspecialcharsbx($requestValues['NAME'] ?: $categoryTag['NAME'])
);

$arButtonsParams = [
    "disabled" => $readOnly,
    "back_url" => "/bitrix/admin/sotbit.seometa_category_tags_list.php?lang=" . LANG
];

$context = new CAdminContextMenu([
    [
        "TEXT" => Loc::getMessage("SEO_META_CT_EDIT_BACK_TO_LIST"),
        "TITLE" => Loc::getMessage("SEO_META_CT_EDIT_BACK_TO_LIST"),
        "LINK" => "sotbit.seometa_category_tags_list.php?lang=" . LANG,
        "ICON" => "btn_list"
    ]
]);
$context->Show();

$tabControl->Buttons($arButtonsParams);
$tabControl->Show();


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>