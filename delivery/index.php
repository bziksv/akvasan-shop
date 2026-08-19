<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Доставка | Условия и сроки доставки заказов в магазине «Аквасан»");
$APPLICATION->SetPageProperty("description", "Ознакомьтесь с условиями доставки в нашем интернет-магазине. Быстрая и надежная доставка по всей России, удобные тарифы и способы получения заказа.");
$APPLICATION->SetTitle("Доставка");
?>

<div class="wrapp-delivery container-main">
    <div class="container">
        <div class="title"><h1>Доставка</h1></div>
        <div class="block-tabs-delivery">
            <div class="left-column">
                <div class="wrapp-tabs-delivery">
                    <ul class="tabs-delivery nav nav-tabs">
                        <li class="active"><a href="#tabs-delivery1">По Воронежу</a></li>
                        <li><a href="#tabs-delivery2">По области</a></li>
                        <li><a href="#tabs-delivery3">Ваш регион</a></li>
                        <li><a href="#tabs-delivery4">Подъем на этаж</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tabs-delivery1">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/delivery-tab1.png" alt="">
                                </div>
                                <div class="text-block-tabs">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.detail",
                                        "delivery_payment",
                                        Array(
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "ADD_ELEMENT_CHAIN" => "N",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "N",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "CHECK_DATES" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DETAIL_URL" => "",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "DISPLAY_DATE" => "Y",
                                            "DISPLAY_NAME" => "Y",
                                            "DISPLAY_PICTURE" => "Y",
                                            "DISPLAY_PREVIEW_TEXT" => "Y",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_CODE" => "",
                                            "ELEMENT_ID" => "50724",
                                            "FIELD_CODE" => array("", ""),
                                            "IBLOCK_ID" => "1",
                                            "IBLOCK_TYPE" => "content",
                                            "IBLOCK_URL" => "",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "MESSAGE_404" => "",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Страница",
                                            "PROPERTY_CODE" => array("", ""),
                                            "SET_BROWSER_TITLE" => "N",
                                            "SET_CANONICAL_URL" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "N",
                                            "SET_META_KEYWORDS" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "N",
                                            "SHOW_404" => "N",
                                            "STRICT_SECTION_CHECK" => "N",
                                            "USE_PERMISSIONS" => "N",
                                            "USE_SHARE" => "N"
                                        )
                                    );?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-delivery2">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/delivery-tab2.png" alt="">
                                </div>
                                <div class="text-block-tabs">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.detail",
                                        "delivery_payment",
                                        Array(
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "ADD_ELEMENT_CHAIN" => "N",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "N",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "CHECK_DATES" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DETAIL_URL" => "",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "DISPLAY_DATE" => "Y",
                                            "DISPLAY_NAME" => "Y",
                                            "DISPLAY_PICTURE" => "Y",
                                            "DISPLAY_PREVIEW_TEXT" => "Y",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_CODE" => "",
                                            "ELEMENT_ID" => "50725",
                                            "FIELD_CODE" => array("", ""),
                                            "IBLOCK_ID" => "1",
                                            "IBLOCK_TYPE" => "content",
                                            "IBLOCK_URL" => "",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "MESSAGE_404" => "",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Страница",
                                            "PROPERTY_CODE" => array("", ""),
                                            "SET_BROWSER_TITLE" => "N",
                                            "SET_CANONICAL_URL" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "N",
                                            "SET_META_KEYWORDS" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "N",
                                            "SHOW_404" => "N",
                                            "STRICT_SECTION_CHECK" => "N",
                                            "USE_PERMISSIONS" => "N",
                                            "USE_SHARE" => "N"
                                        )
                                    );?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-delivery3">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/delivery-tab3.png" alt="">
                                </div>
                                <div class="text-block-tabs">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.detail",
                                        "delivery_payment",
                                        Array(
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "ADD_ELEMENT_CHAIN" => "N",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "N",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "CHECK_DATES" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DETAIL_URL" => "",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "DISPLAY_DATE" => "Y",
                                            "DISPLAY_NAME" => "Y",
                                            "DISPLAY_PICTURE" => "Y",
                                            "DISPLAY_PREVIEW_TEXT" => "Y",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_CODE" => "",
                                            "ELEMENT_ID" => "50726",
                                            "FIELD_CODE" => array("", ""),
                                            "IBLOCK_ID" => "1",
                                            "IBLOCK_TYPE" => "content",
                                            "IBLOCK_URL" => "",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "MESSAGE_404" => "",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Страница",
                                            "PROPERTY_CODE" => array("", ""),
                                            "SET_BROWSER_TITLE" => "N",
                                            "SET_CANONICAL_URL" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "N",
                                            "SET_META_KEYWORDS" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "N",
                                            "SHOW_404" => "N",
                                            "STRICT_SECTION_CHECK" => "N",
                                            "USE_PERMISSIONS" => "N",
                                            "USE_SHARE" => "N"
                                        )
                                    );?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-delivery4">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/delivery-tab4.png" alt="">
                                </div>
                                <div class="text-block-tabs">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.detail",
                                        "delivery_payment",
                                        Array(
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "ADD_ELEMENT_CHAIN" => "N",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "N",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "CHECK_DATES" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DETAIL_URL" => "",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "DISPLAY_DATE" => "Y",
                                            "DISPLAY_NAME" => "Y",
                                            "DISPLAY_PICTURE" => "Y",
                                            "DISPLAY_PREVIEW_TEXT" => "Y",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_CODE" => "",
                                            "ELEMENT_ID" => "50727",
                                            "FIELD_CODE" => array("", ""),
                                            "IBLOCK_ID" => "1",
                                            "IBLOCK_TYPE" => "content",
                                            "IBLOCK_URL" => "",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "MESSAGE_404" => "",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Страница",
                                            "PROPERTY_CODE" => array("", ""),
                                            "SET_BROWSER_TITLE" => "N",
                                            "SET_CANONICAL_URL" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "N",
                                            "SET_META_KEYWORDS" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "N",
                                            "SHOW_404" => "N",
                                            "STRICT_SECTION_CHECK" => "N",
                                            "USE_PERMISSIONS" => "N",
                                            "USE_SHARE" => "N"
                                        )
                                    );?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="img-block">
                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/cars.png" alt="">
                </div>
                <p>Оформить доставку вы можете:</p>
                <ul>
                    <li>По телефону</li>
                    <li>Оставив заказ на сайте</li>
                    <li>У нас в офисе</li>
                </ul>
            </div>
        </div>
        
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>