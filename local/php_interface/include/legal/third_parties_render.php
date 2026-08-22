<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once __DIR__ . '/legal_export_helpers.php';

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
    $parts = [legal_var($service['name'])];
    if (!empty($service['inn'])) {
        $parts[0] .= ' (ИНН ' . legal_var($service['inn']) . ')';
    }

    $line = $parts[0] . ' — ';
    $line .= '<a href="' . legal_h($service['url']) . '" target="_blank" rel="noopener">'
        . legal_var($service['link_label']) . '</a>';
    if (!empty($service['suffix'])) {
        $line .= ', ' . legal_var($service['suffix']);
    }

    return $line;
}

function akvasanLegalRenderThirdPartyConsentLine(array $service): string
{
    $parts = [legal_var($service['name'])];
    if (!empty($service['inn'])) {
        $parts[0] .= ' (ИНН ' . legal_var($service['inn']) . ')';
    }

    $description = $service['link_label'];
    if (!empty($service['suffix'])) {
        $description .= ', ' . $service['suffix'];
    }

    return $parts[0] . ' (' . legal_var($description) . ') — '
        . '<a href="' . legal_h($service['url']) . '" target="_blank" rel="noopener">'
        . legal_var($service['url']) . '</a>';
}

function akvasanLegalRenderThirdPartyRecommendationLine(array $block): string
{
    $links = [];
    foreach ($block['urls'] as $url) {
        $links[] = '<a href="' . legal_h($url) . '" target="_blank" rel="noopener">'
            . legal_var($url) . '</a>';
    }

    return implode(', ', $links) . ' — ' . legal_var($block['text']);
}
