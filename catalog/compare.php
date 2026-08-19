<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Сравнение товаров");
$APPLICATION->SetPageProperty("title", "Сравнение товаров");
$APPLICATION->SetPageProperty("description", "Сравнение товаров — интернет-магазин АкваСан");
?>
<div class="container-compare"><div class="title-compare">Сравнение товаров</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"bprod",
	[
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "5",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action_ccr",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"FIELD_CODE" => [
			0 => "NAME",
			1 => "DETAIL_PICTURE",
		],
		"PROPERTY_CODE" => [],
		"NAME" => "CATALOG_COMPARE_LIST",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"PRICE_CODE" => [
			0 => "Для интернет-магазина",
		],
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"DETAIL_URL" => "/catalog/product/#ELEMENT_CODE#/",
		"OFFERS_FIELD_CODE" => [],
		"OFFERS_PROPERTY_CODE" => [],
		"OFFERS_CART_PROPERTIES" => [],
		"CONVERT_CURRENCY" => "N",
		"HIDE_NOT_AVAILABLE" => "Y",
		"TEMPLATE_THEME" => "blue",
	],
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
