<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!CModule::IncludeModule('niges.cookiesaccept')) {
	return;
}

$arResult = CNigesCookiesAcceptHelper::loadSettings(SITE_ID);

if ($arResult['TEXTBTN'] === '') {
	$arResult['TEXTBTN'] = 'Принять';
}

$legalConfig = include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/legal/config.php';
$cookiePolicyUrl = $legalConfig['urls']['cookie'] ?? '/legal/cookie/';

$arResult['MAINTEXT'] = CNigesCookiesAcceptHelper::sanitizeHtml(
	'Данный веб-сайт использует cookie-файлы в целях предоставления вам лучшего пользовательского опыта на нашем сайте. '
	. 'Продолжая использовать данный сайт, вы соглашаетесь с использованием нами cookie-файлов. '
	. 'Для получения дополнительной информации см. <a href="' . htmlspecialcharsbx($cookiePolicyUrl) . '" target="_blank">Политика Cookie</a>.'
);

$arResult['COOKIE_NAME'] = CNigesCookiesAcceptHelper::getCookieName($arResult['TEXTVER']);
$arResult['OPACITY'] = round(((int)$arResult['BTNOPACITY']) / 100, 2);

$this->IncludeComponentTemplate();
