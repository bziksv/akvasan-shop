<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

function akvasanLegalTemplateExport(): bool
{
    return defined('AKVASAN_LEGAL_TEMPLATE_EXPORT') && AKVASAN_LEGAL_TEMPLATE_EXPORT;
}

function legal_h($value): string
{
    return htmlspecialcharsbx((string) $value);
}

/** Подсветка проектных данных в HTML-шаблонах. На сайте — обычный текст. */
function legal_var($value): string
{
    $escaped = legal_h($value);
    if (akvasanLegalTemplateExport()) {
        return '<span class="project-var">' . $escaped . '</span>';
    }

    return $escaped;
}

/** class для <li> со сторонними сервисами в шаблонах. */
function legal_li_attr(): string
{
    return akvasanLegalTemplateExport() ? ' class="project-var-block"' : '';
}

/** Ссылка: href без подсветки, видимый текст — с подсветкой. */
function legal_link(string $url, ?string $text = null): string
{
    $text = $text ?? $url;

    return '<a href="' . legal_h($url) . '" target="_blank" rel="noopener">' . legal_var($text) . '</a>';
}

function legal_mailto(string $email): string
{
    return '<a href="mailto:' . legal_h($email) . '">' . legal_var($email) . '</a>';
}

function legal_tel(string $phone, string $telHref): string
{
    return '<a href="tel:' . legal_h($telHref) . '">' . legal_var($phone) . '</a>';
}

function legal_internal_link(string $path, string $host): string
{
    return '<a href="' . legal_h($path) . '">' . legal_var($host . $path) . '</a>';
}
