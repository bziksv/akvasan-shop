<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @param string $legalTitle
 * @param string|null $legalSubtitle
 * @param string $contentInclude relative to /local/php_interface/include/legal/
 */
function akvasanRenderLegalPage(string $legalTitle, string $contentInclude, ?string $legalSubtitle = null): void
{
    global $APPLICATION;

    $APPLICATION->SetTitle($legalTitle);
    $APPLICATION->SetPageProperty('title', $legalTitle . ' — АкваСан');
    $APPLICATION->SetPageProperty('description', $legalTitle . ' интернет-магазина АкваСан.');

    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/legal_page_start.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/legal/' . $contentInclude;
    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/legal_page_end.php';
}
