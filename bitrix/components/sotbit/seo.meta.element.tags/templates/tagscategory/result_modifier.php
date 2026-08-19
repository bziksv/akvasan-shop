<?php

$arCategory = [];

foreach ($arResult['ITEMS'] as $item) {
    if ($arCategory[$item['TAGS_DETAIL_ID']]) {
        $arCategory[$item['TAGS_DETAIL_ID']]['ITEMS'][] = $item;
    } else {
        $arCategory[$item['TAGS_DETAIL_ID'] ?: 1] = [
            'TITLE' => $item['TAGS_DETAIL_NAME'],
            'SORT' => $item['TAGS_DETAIL_SORT'],
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