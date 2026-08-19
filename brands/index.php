<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров - бренд");

use Bitrix\Main\Application;

$iblock = 5;
$request = Application::getInstance()->getContext()->getRequest();

$code = explode("/", $APPLICATION->GetCurPage());
//$brand = $request["brand"] ? strtolower($request["brand"]) : $code[2];
$brand = $code[2];

if (strpos($brand, "?")) {
    $brand = stristr($brand,'?',true);
}

$section = $code[3];

$name = '';

if (strlen($brand) > 0) {
    $propFilter = array();                
    $propertyEnums = CIBlockPropertyEnum::GetList(Array("value" => "ASC", "SORT" => "ASC"), Array("IBLOCK_ID" => $iblock, "CODE" => "BREND%"));
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
} else {
    $cache = new CPHPCache();
    $cache_time = 36000;
    $cache_id = 'listBrandCzebra';
    $cache_path = '/listBrandCzebra/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path)) {
        $res = $cache->GetVars();
        if (is_array($res["arFirstChar"]) && (count($res["arFirstChar"]) > 0)) {
            $arFirstChar = $res["arFirstChar"];
        }
        if (is_array($res["listBrand"]) && (count($res["listBrand"]) > 0)) {
            $listBrand = $res["listBrand"];
        }
    }

    if (!is_array($listBrand) && !is_array($arFirstChar)) {
        $iblock = 5;
        $propertyEnums = CIBlockPropertyEnum::GetList(Array("value" => "ASC", "sort" => "ASC"), Array("IBLOCK_ID" => $iblock, "CODE" => "BREND%"));
        while($enum_fields = $propertyEnums->GetNext()) {
            $count = CIBlockElement::GetList([], ["IBLOCK" => $iblock, "ACTIVE" => "Y", "PROPERTY_BREND_16" => $enum_fields["ID"]], false, ["nPageSize"=>1], ["ID", "IBLOCK"]);
            if ($count->SelectedRowsCount() > 0) {
                $firstChar = ToUpper(substr($enum_fields["VALUE"], 0, 1));
                if (is_numeric($firstChar)) {
                    $firstChar = "0-9";
                }
                $arFirstChar[] = $firstChar;
                $listBrand[] = [
                    "NAME" => $enum_fields["VALUE"],
                    "CODE" => Cutil::translit($enum_fields["VALUE"],"ru",array()),
                    "FIRST_CHAR" => $firstChar
                ];
            }
        }
        $arFirstChar = array_unique($arFirstChar);

        if ($cache_time > 0) {
            $cache->StartDataCache($cache_time, $cache_id, $cache_path);
            $cache->EndDataCache(array(
                "arFirstChar" => $arFirstChar,
                "listBrand" => $listBrand,
            ));
        }
    }
}
?>
<?if (strlen($brand) == 0 ):?>

<div class="wrapp-filter-name-string">
        <div class="container">
            <div class="item-filter-name-string">
                <a id = "all-brands" class="active-filter-string" href="#">ВСЕ</a>
            </div>
            <?foreach($arFirstChar as $val):?>
                <div class="item-filter-name-string">
                    <a href="#"><?=$val?></a>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <?global $USER;?>
    <div class="wrapp-brand">
        <div class="container">
            <div class="block-brand-item">
            <?foreach($listBrand as $key => $arItem):?>
                <?if($key % 6 == 0):?>
                    </div><div class="block-brand-item">
                <?endif;?>
                <div class="item-brand-card" data-search-char="<?=$arItem["FIRST_CHAR"]?>">
                    <a href="/brands/<?=$arItem["CODE"]?>/">
                        <span class="card-img-brand">
                            <?
                            $file = "/upload/brands/" . Cutil::translit($arItem["NAME"],"ru",array()) . ".png";
                            if (file_exists($_SERVER["DOCUMENT_ROOT"].$file)) : 
                            ?> 
                            <img src="<?=$file?>" alt="<?=$arItem["NAME"]?>">
                            <?endif?>
                        </span>
                        <span class="name-brand"><?=$arItem["NAME"]?></span>
                        <?if ($USER->IsAdmin() &&  !file_exists($_SERVER["DOCUMENT_ROOT"].$file)) :?>
                            <span class="name-brand" style="font-size:10px"><?=$file?></span>
                        <?endif?>
                    </a>
                </div>
            <?endforeach?>
        </div>
    </div>



    <script>

        $('.item-filter-name-string a').click(function(){
            $('.item-filter-name-string a').removeClass('active-filter-string');
            $(this).addClass('active-filter-string');
            $('.item-brand-card').hide();
            $('.item-brand-card[data-search-char="'+ $(this).text()+'"]').show();
            return false;
        });

        $('#all-brands').click(function(){
            $('.item-brand-card').show();
        });

    </script>

<?elseif(count($propFilter) == 0) :
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404","Y");  ?>
    <div class="container-main">
    <div class="title">
        <h1>Ошибка 404</h1>
    </div><br/>
    <p>Что-то пошло не так. Предлагаем вернуться <a href="/">на главную страницу</a>.</p>
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
    <div class="container-sorting">
        <span class="title-sorting"><?=$name?></span>
        <span class="link-sorting">
        <?foreach ($arAvailableSort as $key => $val):?>
            <?$newSort = ($sort == $val[0]) ? ($sort_order == 'desc' ? 'asc' : 'desc') : $arAvailableSort[$key][1];?>
            <a href="<?=$APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort,  array('sort', 'order'))?>" class="<?if($sort == $val[0]):?> <?if($sort_order=="asc" || $sort_order=="desc"):?>selected<?endif?><?endif?>"><span class="<?if($sort_order=="desc"):?>selected-desc<?endif?>"></span><?=$arAvailableSort[$key][2]?></a>
        <?endforeach?>  
        </span>
        <span class="display-products" id="container-sorting-top">
            <div class="eye"></div>
            <?for($i=14;$i<=112;$i*=2):?>
                <a href="<?=$APPLICATION->GetCurPageParam("COUNT=$i", array("COUNT","clear_cache"));?>" <?if($i == $request["COUNT"] || (strlen($request["COUNT"]) == 0 && $i==14)):?>class="selected"<?endif;?> ><?=$i?></a>
            <?endfor;?>
        </span>
        
        <a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison"></span>)</a>
    </div>

<?
// global $USER;
// if($USER->IsAdmin()):?>

<?
//Получение разделов каталога с товарами, где есть соответствующий бренд
$idSectionBrand = [];
$resElementBrand = CIBlockElement::GetList([], ["IBLOCK_ID" => "5", "ACTIVE" => "Y", "PROPERTY_BREND_16_VALUE" => $name], false, false, ["ID", "NAME", "IBLOCK_SECTION_ID"]);
while($arElementBrand = $resElementBrand -> GetNext()){
    if($arElementBrand["IBLOCK_SECTION_ID"]){
        $idSectionBrand[$arElementBrand["IBLOCK_SECTION_ID"]] = $arElementBrand["IBLOCK_SECTION_ID"];
    }
}

//Получение родительских разделов
$resSection = CIBlockSection::GetList([], ["IBLOCK_ID" => "5", "ACTIVE" => "Y", "ID" => $idSectionBrand], false);
while($arSections = $resSection->GetNext()){
    if($arSections["DEPTH_LEVEL"] != 1 && !in_array($arSections["IBLOCK_SECTION_ID"], $idSectionBrand)){
        $idSectionBrand[$arSections["IBLOCK_SECTION_ID"]] = $arSections["IBLOCK_SECTION_ID"];
    }
}
?>
<?if($idSectionBrand):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"brand_section_list",
	Array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "",
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array("", ""),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array("", ""),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "4",
        "VIEW_MODE" => "LIST",
        "BRAND_CODE" => $brand,
        "BRAND_NAME" => $name,
        "ID_BRAND" => $idSectionBrand,
        "XML_ID_BRAND" => $propFilter[0]["XML_ID"]
	)
);?>
<?endif;?>
<div class="right-container col-lg-8 col-lg-push-4 col-md-8 col-md-push-4">
<?//endif;?>


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
                        //"COMPATIBLE_MODE" => "Y",
                        //"COMPOSITE_FRAME_MODE" => "A",
                        //"COMPOSITE_FRAME_TYPE" => "AUTO",
                        "CONVERT_CURRENCY" => "Y",
                        //"CUSTOM_FILTER" => "",
                        "DETAIL_URL" => "",
                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        //"DISPLAY_COMPARE" => "N",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_SORT_FIELD2" => "id",
                        "ELEMENT_SORT_ORDER2" => "desc",
                        "ELEMENT_SORT_FIELD" => $sort,//$arAvailableSort[$key_sort][0],
                        "ELEMENT_SORT_ORDER" => $sort_order,// $arAvailableSort[$key_sort][1],
                        //"ENLARGE_PRODUCT" => "STRICT",
                        "FILTER_NAME" => "arFilter",
                        "HIDE_NOT_AVAILABLE" => "Y",
                        "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                        "IBLOCK_ID" => $iblock,
                        "IBLOCK_TYPE" => "1c_catalog",
                        //"INCLUDE_SUBSECTIONS" => "Y",
                        "LABEL_PROP" => array(),
                        //"LAZY_LOAD" => "N",
                        "LINE_ELEMENT_COUNT" => "4",
                        //"LOAD_ON_SCROLL" => "N",
                        "MESSAGE_404" => "",
                        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                        "MESS_BTN_BUY" => "Купить",
                        "MESS_BTN_DETAIL" => "Подробнее",
                        "MESS_BTN_SUBSCRIBE" => "Подписаться",
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "META_DESCRIPTION" => "-",
                        "META_KEYWORDS" => "-",
                        //"OFFERS_LIMIT" => "5",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => "full",
                        "PAGER_TITLE" => "Товары",
                        "PAGE_ELEMENT_COUNT" => (strlen($request["COUNT"]) > 0) ? $request["COUNT"] : 14,
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRICE_CODE" => array("Для интернет-магазина"),
                        "PRICE_VAT_INCLUDE" => "Y",
                        //"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "PRODUCT_PROPERTIES" => array(),
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "PRODUCT_QUANTITY_VARIABLE" => "QUANITY",
                        //"PRODUCT_ROW_VARIANTS" => "[{\"VARIANT\":2,\"BIG_DATA\":false},{\"VARIANT\":2,\"BIG_DATA\":false},{\"VARIANT\":2,\"BIG_DATA\":false},{\"VARIANT\":2,\"BIG_DATA\":false},{\"VARIANT\":2,\"BIG_DATA\":false},{\"VARIANT\":2,\"BIG_DATA\":false}]",
                        //"PRODUCT_SUBSCRIPTION" => "Y",
                        "PROPERTY_CODE" => array("BREND", ""),
                        //"PROPERTY_CODE_MOBILE" => array(),
                        //"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                        //"RCM_TYPE" => "personal",
                        "SECTION_CODE" => $section,
                        "SECTION_ID" => "",
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "SECTION_URL" => "",
                        //"SECTION_USER_FIELDS" => array("",""),
                        "SEF_MODE" => "Y",
                        "SEF_URL_TEMPLATES" => Array("compare" => "compare.php?action=#ACTION_CODE#", "element" => "brands/#ELEMENT_CODE#/", "section" => "#SECTION_CODE_PATH#/", "sections" => "", "smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/"),
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
                        //"SHOW_FROM_SECTION" => "N",
                        "SHOW_MAX_QUANTITY" => "N",
                        "SHOW_OLD_PRICE" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        //"SHOW_SLIDER" => "Y",
                        "TEMPLATE_THEME" => "blue",
                        //"USE_ENHANCED_ECOMMERCE" => "N",
                        "USE_MAIN_ELEMENT_SECTION" => "N",
                        "USE_PRICE_COUNT" => "N",
                        "USE_PRODUCT_QUANTITY" => "Y",
                        "BRAND" => "Y"
                    )
                ); ?>
                <?//if($USER->IsAdmin()):?>
                </div> <!-- right-container -->
                <?//endif;?>
            </div>
        </div>
    </div>
<? endif; ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
