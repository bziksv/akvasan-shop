<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

function akvasanLegalThirdPartiesData(): array
{
    static $data = null;
    if ($data === null) {
        $data = include __DIR__ . '/third_parties_data.php';
    }

    return $data;
}

function akvasanLegalRenderThirdPartyPolicyLine(array $service): string
{
    $parts = [htmlspecialcharsbx($service['name'])];
    if (!empty($service['inn'])) {
        $parts[0] .= ' (ИНН ' . htmlspecialcharsbx($service['inn']) . ')';
    }

    $line = $parts[0] . ' — ';
    $line .= '<a href="' . htmlspecialcharsbx($service['url']) . '" target="_blank" rel="noopener">'
        . htmlspecialcharsbx($service['link_label']) . '</a>';
    if (!empty($service['suffix'])) {
        $line .= ', ' . htmlspecialcharsbx($service['suffix']);
    }

    return $line;
}

function akvasanLegalRenderThirdPartyConsentLine(array $service): string
{
    $parts = [htmlspecialcharsbx($service['name'])];
    if (!empty($service['inn'])) {
        $parts[0] .= ' (ИНН ' . htmlspecialcharsbx($service['inn']) . ')';
    }

    $description = $service['link_label'];
    if (!empty($service['suffix'])) {
        $description .= ', ' . $service['suffix'];
    }

    return $parts[0] . ' (' . htmlspecialcharsbx($description) . ') — '
        . '<a href="' . htmlspecialcharsbx($service['url']) . '" target="_blank" rel="noopener">'
        . htmlspecialcharsbx($service['url']) . '</a>';
}

function akvasanLegalRenderThirdPartyRecommendationLine(array $block): string
{
    $links = [];
    foreach ($block['urls'] as $url) {
        $links[] = '<a href="' . htmlspecialcharsbx($url) . '" target="_blank" rel="noopener">'
            . htmlspecialcharsbx($url) . '</a>';
    }

    return implode(', ', $links) . ' — ' . htmlspecialcharsbx($block['text']);
}
