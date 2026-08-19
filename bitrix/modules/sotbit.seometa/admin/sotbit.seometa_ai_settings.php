<?php

global $APPLICATION;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

const MODULE_NAME = 'sotbit.seometa';

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

if (!Loader::includeModule(MODULE_NAME)) {
    CAdminMessage::ShowMessage(Loc::getMessage('SEO_META_AI_INCLUDE'));
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return false;
}


$POST_RIGHT = $APPLICATION->GetGroupRight(MODULE_NAME);
if ($POST_RIGHT < "R") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

$APPLICATION->SetTitle(Loc::getMessage('SEO_META_AI_TITLE'));

if (CCSeoMeta::ReturnDemo() == 2) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_DEMO") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}
if (CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_DEMO_END") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

\Bitrix\Main\UI\Extension::load("ui.hint");

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage('SEO_META_AI_TAB'),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage('SEO_META_AI_TAB_TITLE')
    ],
];

$tabControl = new CAdminForm("tabControl", $aTabs);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$requestValues = $request->getValues();

$mainSettings = \Sotbit\Seometa\AI\EndPoints\EndpointsContainer::getMainSettings();

$skipChek = [
    'CHECK_BALANCE',
    'PROXY_ADDRESS',
    'PROXY_PORT',
    'PROXY_LOGIN',
    'PROXY_PASSWORD',
];

if ($request->isPost() && ($request->get('save') || $request->get('apply')) && $POST_RIGHT == "W" && check_bitrix_sessid()) {

    if ($enable = $request->get('AI_FUNC_INC')) {
        Option::set(MODULE_NAME, 'AI_FUNC_INC', $enable);
    } else {
        Option::set(MODULE_NAME, 'AI_FUNC_INC', 'N');
    }

    $errMess = '';
    if ($main = $request->get('MAIN_SETTINGS')) {
        foreach ($main as $key => $value) {
            if (in_array($key, $skipChek)) {
                continue;
            }
            if ($value === '' && $key === 'API_KEY' && $main['MODEL'] !== '0') {
                $errMess .= Loc::getMessage('SEO_META_AI_EMPTY_VALUE', ['#VAL#' => Loc::getMessage('SEO_META_AI_' . $key . '_' . strtoupper($main['MODEL']))]) . '<br>';
            } elseif(!$value && $key !== 'API_KEY') {
                $errMess .= Loc::getMessage('SEO_META_AI_EMPTY_VALUE', ['#VAL#' => Loc::getMessage('SEO_META_AI_' . $key)]) . '<br>';
            }
        }
        if ($errMess === '') {
            $main = serialize($main);
            Option::set(MODULE_NAME, 'MAIN_SETTINGS', $main);
        }
    }
}

if ($errMess) {
    Option::set(MODULE_NAME, 'AI_FUNC_INC', 'N');
    CAdminMessage::ShowMessage([
        "MESSAGE" => Loc::getMessage('SEO_META_AI_FUNC_NOT_ENABLED') . $errMess
    ]);
}

$tabControl->Begin(["FORM_ACTION" => $APPLICATION->GetCurPageParam()]);

$tabControl->BeginNextFormTab();

$tabControl->BeginCustomField("HID", '');
echo bitrix_sessid_post();
$tabControl->EndCustomField("HID");

if(!$errMess) {
    $checked = Option::get(MODULE_NAME, 'AI_FUNC_INC') === 'Y';
} else {
    $checked = false;
    $requestValues['AI_FUNC_INC'] = 'N';
}


$tabControl->AddCheckBoxField('AI_FUNC_INC',
    Loc::getMessage('SEO_META_AI_ENABLE_FUNC'),
    false,
    'Y',
    $requestValues['AI_FUNC_INC'] === 'Y' ?: $checked
);

$aiClasses = \Sotbit\Seometa\AI\EndPoints\EndpointsContainer::getAiModels();

$reference = [
    'REFERENCE_ID' => [
        0,
    ],
    'REFERENCE' => [
        Loc::getMessage('SEO_META_AI_CHOOSE_MODEL'),
    ],
];

foreach ($aiClasses as $aiClass) {
    $reference['REFERENCE_ID'][] = $aiClass;
    $reference['REFERENCE'][] = $aiClass;
}

$model = $requestValues['MAIN_SETTINGS']['MODEL'] ?: $mainSettings['MODEL'];

$tabControl->BeginCustomField("MODEL", Loc::getMessage("SEO_META_AI_MODEL"), true);
echo '<tr id="tr_MODEL">
            <td width="40%">' . $tabControl->GetCustomLabelHTML() . '</td>
            <td width="60%">
                ' . SelectBoxFromArray('MAIN_SETTINGS[MODEL]',
        $reference,
        $model,
        '',
        'style="min-width:350px"',
        true,
        'tabControl_form') . '
            </td>
        </tr>';
$tabControl->EndCustomField("MODEL");
if ($model) {
    $container = new \Sotbit\Seometa\AI\EndPoints\EndpointsContainer();
    $container->{$model}->render($tabControl, $requestValues);
}
$arButtonsParams = [
    'disabled' => false,
    'btnApply' => false
];
$tabControl->Buttons($arButtonsParams);
$tabControl->Show(); ?>
    <style>
        .form-control-hint {
            display: flex;
            justify-content: end;
            align-items: center;
        }
    </style>

    <script>
        BX.ready(function () {
            BX.UI.Hint.init(BX('adm-workarea'));

            const temperature = document.querySelector('#temperature');
            const max_tokens = document.querySelector('#max_tokens');

            temperature?.addEventListener('input', function (e) {
                if (e.target.value.length > 3) {
                    e.target.value = e.target.value.substring(0, 3);
                }

                if (!e.target.value) {
                    e.target.value = 0.1;
                }
                if(e.target.value > 2) {
                    e.target.value = 2;
                }
            });
            max_tokens?.addEventListener('input', function (e) {
                if (e.target.value <= 0) {
                    e.target.value = 1;
                }
            });
        })
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

