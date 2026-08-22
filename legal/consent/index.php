<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/legal/render.php';
akvasanRenderLegalPage(
    'Согласие на обработку персональных данных, включая cookie',
    'consent_content.php',
    'в том числе файлы cookie'
);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
