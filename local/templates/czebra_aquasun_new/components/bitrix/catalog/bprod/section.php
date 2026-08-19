<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Bitrix\Main\Loader;

$arFilter = array(
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "ACTIVE" => "Y",
    "GLOBAL_ACTIVE" => "Y",
);
if (0 < intval($arResult["VARIABLES"]["SECTION_ID"])) {
    $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
}
elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"]) {
    $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
}

$obCache = new CPHPCache();
if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
    $arCurSection = $obCache->GetVars();
} elseif ($obCache->StartDataCache()) {
    $arCurSection = array();
    if (Loader::includeModule("iblock")) {
        $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID", "NAME", "UF_COUNT", "UF_SORT_ASC"));

        if (defined("BX_COMP_MANAGED_CACHE")) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache("/iblock/catalog");

            if ($arCurSection = $dbRes->Fetch())
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);

            $CACHE_MANAGER->EndTagCache();
        } else {
            if (!$arCurSection = $dbRes->Fetch())
                $arCurSection = array();
        }
    }
    $obCache->EndDataCache($arCurSection);
}
if (!isset($arCurSection)) {
    $arCurSection = array();
}

//Получаем быстрые ссылки
$arSelect = Array("ID", "IBLOCK_ID", "NAME");
$arFilter = Array("IBLOCK_ID" => 11, "ACTIVE" => "Y", "PROPERTY_CATEGORY" => $arCurSection['ID'] );
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
$arFastLinkAll = array();
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
    $arFastLink = array();
	
    foreach($arProps['ITEM']["VALUE"] as $item) {
		if (is_array($item)) {
			$arFastLink[$item["SUB_VALUES"]["ITEM_SORT"]["VALUE"]] = array(
				"NAME" => $item["SUB_VALUES"]["ITEM_NAME"]["VALUE"],
				"LINK" => $item["SUB_VALUES"]["ITEM_LINK"]["VALUE"],
			);			
		}
    }
    ksort($arFastLink);
    $arFastLinkAll[] = array(
        "NAME" => $arFields["NAME"],
        "ITEM" => $arFastLink,
    );
}
?>

<div class="right-container col-lg-8 col-lg-push-4 col-md-8 col-md-push-4">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "prod_section",
        Array(
            "PATH" => "",
            "SITE_ID" => "s1",
            "START_FROM" => "1",
        )
    );?>

    <? if($arFastLinkAll): ?>
        <div class="quick-link-wrapper">
            <div class="quick-link <?=(showAllQuickLinksButton($arFastLinkAll)) ? 'show-all-tags' : ''?>">
                <?foreach($arFastLinkAll as $arFastLink):?>
                    <div><span><?=$arFastLink["NAME"]?></span>
                        <?$url = Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getRequestUri();?>
                        <?foreach($arFastLink["ITEM"] as $arItem):?>
                            <?if($arItem['LINK'] == $url):?>
                                <a href="<?=$APPLICATION->GetCurPageParam("", array("set_filter")); ?>" class="active-quick-link"><?=$arItem['NAME']?></a>
                            <?else:?>
                                <a href="<?=$arItem['LINK']?>"><?=$arItem['NAME']?></a>
                            <?endif?>
                        <?endforeach;?>
                    </div>
                <?endforeach;?>
            </div>
        </div>

        <? if(showAllQuickLinksButton($arFastLinkAll)): ?>
            <div class="quick-link-button-wrapper">
                <a href="#" class="quick-link-button">Показать все</a>
            </div>
        <? endif; ?>

    <? endif; ?>

    <?
    //проверка на первый уровень раздела
    $resDepthFirst = CIBlockSection::GetList([], ["IBLOCK_ID" => $arParams[""], "ID" => $arResult["VARIABLES"]["SECTION_ID"], "ACTIVE" => "Y"], false, ["NAME", "ID", "DEPTH_LEVEL"], false);
    $isFirstLevel = false;
    while($arSectionDepthFirst = $resDepthFirst -> GetNext()){
        if($arSectionDepthFirst["DEPTH_LEVEL"] == 1){
            $isFirstLevel = true;
        }
    }
    ?>
    <?global $USER;?>
    <?GLOBAL ${$arParams["FILTER_NAME"]};
    ${$arParams["FILTER_NAME"]}[">CATALOG_PRICE_1"] = 10;
    if($isFirstLevel) {
        //скрываем товары из скрытых разделов на первом уровне каталога
        $resHidden = CIBlockSection::GetList([], ["IBLOCK_ID" => $arParams["IBLOCK_ID"], /*"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],*/ "UF_HIDE_FROM_1_LEVEL" => "1"], false, ["NAME", "ID", "UF_HIDE_FROM_1_LEVEL"], false);
        $idSectionHidden = [];
        while($arSectionHidden = $resHidden -> GetNext()){
            $idSectionHidden[] = $arSectionHidden["ID"];
        }
        ${$arParams["FILTER_NAME"]}["!IBLOCK_SECTION_ID"] = $idSectionHidden;
    }
	?>

    <?
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.smart.filter",
        //"",
        "bprod",
        array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => $arCurSection['ID'],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SAVE_IN_SESSION" => "N",
            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
            "XML_EXPORT" => "Y",
            "SECTION_TITLE" => "NAME",
            "SECTION_DESCRIPTION" => "DESCRIPTION",
            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            "SEF_MODE" => $arParams["SEF_MODE"],
            "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
            "PREFILTER_NAME" => $arParams["FILTER_NAME"]
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
    ?>



    <?
	$catalog_sort = ($arCurSection['UF_SORT_ASC']) ? "SORT" : "catalog_PRICE_1";

    $arAvailableSort = array(
        "shows" => Array("SORT", "asc", "По популярности"),
        "price_low" => Array('catalog_PRICE_1', "asc", "По цене"),
    );
    $sort = array_key_exists("sort", $_REQUEST) && array_key_exists($_REQUEST["sort"], $arAvailableSort) ? $arAvailableSort[$_REQUEST["sort"]][0] : $catalog_sort;
    $sort_order = array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ? ToLower($_REQUEST["order"]) : 'asc';
    ?>
    <div class="container-sorting">
        <span class="title-sorting">
            <?=$arCurSection["NAME"]?>
        </span>
        <span class="link-sorting">
        <?foreach ($arAvailableSort as $key => $val):?>
            <?$newSort = ($sort == $val[0]) ? ($sort_order == 'desc' ? 'asc' : 'desc') : $arAvailableSort[$key][1];?>
            <a href="<?=$APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort,  array('sort', 'order'))?>" class="<?if($sort == $val[0]):?> <?if($sort_order=="asc" || $sort_order=="desc"):?>selected<?endif?><?endif?>"><span class="<?if($sort_order=="desc"):?>selected-desc<?endif?>"></span><?=$arAvailableSort[$key][2]?></a>
        <?endforeach?>
        </span>

        <span class="link-mobile-filter">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#smartFilterModal">
                <span></span>
                Фильтры
            </a>
        </span>

		<? if(empty($arCurSection['UF_COUNT'])): ?>
        <span class="display-products" id="container-sorting-top">
            <div class="eye"></div>
            <?for($i=14;$i<=112;$i*=2):?>
                <a href="<?=$APPLICATION->GetCurPageParam("COUNT=$i", array("COUNT","clear_cache"));?>" <?if($i == $_REQUEST["COUNT"] || (strlen($_REQUEST["COUNT"]) == 0 && $i==14)):?>class="selected"<?endif;?> ><?=$i?></a>
            <?endfor;?>
        </span>
		<? else: ?>
			<?$_REQUEST["COUNT"] = $arCurSection['UF_COUNT'];?>
		<? endif; ?>

        <a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison"></span>)</a>

    </div>

    <?
        $APPLICATION->IncludeFile("/includes/modal.php", ["ID" => "smartFilterModal", "TITLE" => "Фильтры"], ["SHOW_BORDER" => false]);
    ?>

    <?$intSectionID = $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "bprod",
        array(
			"SECTION_USER_FIELDS" => ["UF_*"],
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_SORT_FIELD" => $sort,//$arAvailableSort[$key_sort][0],
            "ELEMENT_SORT_ORDER" => $sort_order,// $arAvailableSort[$key_sort][1],
            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "MESSAGE_404" => $arParams["~MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
            "PAGE_ELEMENT_COUNT" => (strlen($_REQUEST["COUNT"]) > 0) ? $_REQUEST["COUNT"] : $arParams["PAGE_ELEMENT_COUNT"],
            "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
            "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
            "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "LAZY_LOAD" => $arParams["LAZY_LOAD"],
            "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
            "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
            "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
            "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
            "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
            'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

            'LABEL_PROP' => $arParams['LABEL_PROP'],
            'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
            'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
            'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
            'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
            'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
            'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
            'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
            'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
            'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
            'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
            'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
            'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
            'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
            'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
            'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
            'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
            'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
            'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
            'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
            'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
            'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

            'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
            'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
            'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

            'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            "ADD_SECTIONS_CHAIN" => "Y",
            'ADD_TO_BASKET_ACTION' => $basketAction,
            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
            'COMPARE_NAME' => $arParams['COMPARE_NAME'],
            'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
            'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
            'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
        ),
        $component
    );
    ?>

	<?
	$APPLICATION->IncludeComponent(
   "sotbit:seo.meta",
   ".default",
   Array(
   "FILTER_NAME" => $arParams["FILTER_NAME"],
        "SECTION_ID" => $arCurSection['ID'],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
	   )
	);
	?>

</div>
