<?php

$arCategory = [];

foreach ($arResult['ITEMS'] as $item) {
    if ($arCategory[$item['TAGS_SECTION_ID']]) {
        $arCategory[$item['TAGS_SECTION_ID']]['ITEMS'][] = $item;
    } else {
        $arCategory[$item['TAGS_SECTION_ID'] ?: 1] = [
            'TITLE' => $item['TAGS_SECTION_NAME'],
            'SORT' => $item['TAGS_SECTION_SORT'],
            'ITEMS' => [$item],
        ];
    }
}

if($arParams['SORT_ORDER_TAGS']) {
    $sort = $arParams['SORT_ORDER_TAGS'];
}

if($sort === 'asc') {
    uasort($arCategory, function ($a, $b) {
        return $a['SORT'] <=> $b['SORT'];
    });
} else {
    uasort($arCategory, function ($a, $b) {
        return $b['SORT'] <=> $a['SORT'];
    });
}

$arResult['CATEGORY_TAGS'] = $arCategory;