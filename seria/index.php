<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров - бренд");

use Bitrix\Main\Application;

$iblock = 5;

$request = Application::getInstance()->getContext()->getRequest();
$brand = strtolower($request["seria"]);
if (strpos($brand, "?")) {
    $brand = stristr($brand,'?',true);
}
$name = '';

if (strlen($brand) > 0) {
    $propFilter = array();                
    $propertyEnums = CIBlockPropertyEnum::GetList(Array("value" => "ASC", "SORT" => "ASC"), Array("IBLOCK_ID" => $iblock, "CODE" => "SERIYA%"));
    while ($enumFields = $propertyEnums->GetNext()) {
        if ($brand == strtolower(Cutil::translit($enumFields["VALUE"],"ru",array()))) {
            $propFilter[] = $enumFields;
        }
    }

    $tempFilter = array("LOGIC" => "OR");
    foreach ($propFilter as $val) {
        $tempFilter[] = array("PROPERTY_".$val['PROPERTY_CODE']."_VALUE" => $val['VALUE']);
        $name = $val['VALUE'];
    }
    global $arFilter;
    $arFilter[] = $tempFilter;

    $APPLICATION->SetPageProperty("title", "Каталог товаров - ".$name);
}
?><?if (strlen($brand) == 0 || count($propFilter) == 0) :
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404","Y");  ?>
<div class="container-main">
	<div class="title">
		<h1>Ошибка 404</h1>
	</div>
 <br>
	<p>
		 Что-то пошло не так. Предлагаем вернуться <a href="/">на главную страницу</a>.
	</p>
</div>
 <?
else: ?> 
<?
	$arAvailableSort = array(
		"shows" => Array("SORT", "desc", "По популярности"),
		"price_low" => Array('catalog_PRICE_1', "asc", "По цене"),
	);
	$sort = array_key_exists("sort", $_REQUEST) && array_key_exists($_REQUEST["sort"], $arAvailableSort) ? $arAvailableSort[$_REQUEST["sort"]][0] : 'SORT';
	$sort_order = array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ? ToLower($_REQUEST["order"]) : 'desc';
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"bprod",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/cart/",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CONVERT_CURRENCY" => "Y",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => $sort,
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => $sort_order,
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arFilter",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => $iblock,
		"IBLOCK_TYPE" => "1c_catalog",
		"LABEL_PROP" => array(),
		"LINE_ELEMENT_COUNT" => "4",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "full",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => (strlen($request["COUNT"])>0)?$request["COUNT"]:14,
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array("Для интернет-магазина"),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "QUANITY",
		"PROPERTY_CODE" => array("BREND",""),
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("compare"=>"compare.php?action=#ACTION_CODE#","element"=>"brands/#ELEMENT_CODE#/","section"=>"#SECTION_CODE_PATH#/","sections"=>"","smart_filter"=>"#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/"),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "Y"
	)
);?>
<? endif; ?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>