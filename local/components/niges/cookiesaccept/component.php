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
$cookieUrl = htmlspecialcharsbx($legalConfig['urls']['cookie'] ?? '/legal/cookie/');
$recommendationUrl = htmlspecialcharsbx($legalConfig['urls']['recommendation'] ?? '/legal/recommendation/');
$personalDataUrl = htmlspecialcharsbx($legalConfig['urls']['personal_data'] ?? '/legal/personal-data/');
$consentUrl = htmlspecialcharsbx($legalConfig['urls']['consent'] ?? '/legal/consent/');

$arResult['MAINTEXT'] = CNigesCookiesAcceptHelper::sanitizeHtml(
	'Для обеспечения корректной работы сайта мы используем файлы '
	. '<a href="' . $cookieUrl . '" target="_blank">cookie</a> '
	. 'и <a href="' . $recommendationUrl . '" target="_blank">рекомендательные технологии</a>. '
	. 'Сбор информации необходим для персонализации контента, анализа посещаемости и оптимизации функционала. '
	. 'Продолжая пользоваться сайтом, вы даёте согласие на обработку персональных данных в соответствии с '
	. '<a href="' . $personalDataUrl . '" target="_blank">Политикой обработки персональных данных</a> '
	. 'и <a href="' . $consentUrl . '" target="_blank">Согласием на обработку персональных данных</a>. '
	. 'Вы можете отключить cookies в настройках браузера.'
);

$arResult['COOKIE_NAME'] = CNigesCookiesAcceptHelper::getCookieName($arResult['TEXTVER']);
$arResult['OPACITY'] = round(((int)$arResult['BTNOPACITY']) / 100, 2);

$this->IncludeComponentTemplate();
