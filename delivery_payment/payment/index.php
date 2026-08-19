<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
?>
<div class="wrapp-delivery wrapp-payment container-main">
    <div class="container">
        <div class="title"><h1>Оплата</h1></div>
        <div class="block-tabs-delivery">
            <div class="left-column">
                <div class="wrapp-tabs-delivery">
                    <ul class="tabs-delivery nav nav-tabs">
                        <li class="active"><a href="#tabs-payment1">Наличными</a></li>
                        <li><a href="#tabs-payment2">Картой</a></li>
                        <li><a href="#tabs-payment3">Безнал (юр.)</a></li>
                        <li><a href="#tabs-payment4">Безнал (физ.)</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tabs-payment1">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/payment-tab1.png" alt="">
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
                                            "ELEMENT_ID" => "50728",
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
                        <div class="tab-pane fade" id="tabs-payment2">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/payment-tab2.png" alt="">
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
                                            "ELEMENT_ID" => "50729",
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
                        <div class="tab-pane fade" id="tabs-payment3">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/payment-tab3.png" alt="">
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
                                            "ELEMENT_ID" => "50730",
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
                        <div class="tab-pane fade" id="tabs-payment4">
                            <div class="block-tabs-content">
                                <div class="img-block-tabs">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/payment-tab4.png" alt="">
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
                                            "ELEMENT_ID" => "50731",
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
            <div class="right-column right-column-payment">
                <div class="img-block">
                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/right-payment.png" alt="">
                </div>
                <p>Оформить доставку вы можете:</p>
                <ul>
                    <li>В нашем магазине</li>
                    <li>При доставке товара (водителю)</li>
                    <li>Безналичным способом оплаты</li>
                </ul>
            </div>
        </div>
        
    </div>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>