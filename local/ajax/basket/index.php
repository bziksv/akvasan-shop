<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Composite\Helper::setEnabled(false);

use Bitrix\Main\Application;
use Czebra\BFS\Basket;

$request = Application::getInstance()->getContext()->getRequest();

if($request['action'] == 'add') {
    $param = array(
        "PRODUCT_ID" => $request["id"],
        "QUANITY" => $request["quanity"],
    );

    Basket::Add($param);

    \Bitrix\Main\Loader::includeModule('sale');
    $fuserId = \Bitrix\Sale\Fuser::getId(true);
    \Bitrix\Sale\BasketComponentHelper::updateFUserBasketQuantity($fuserId, SITE_ID);

    $APPLICATION->IncludeComponent(
        "bitrix:sale.basket.basket.line",
        "bprod",
        Array(
            "HIDE_ON_BASKET_PAGES" => "N",
            "PATH_TO_AUTHORIZE" => "",
            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
            "PATH_TO_PERSONAL" => SITE_DIR."personal/",
            "PATH_TO_PROFILE" => SITE_DIR."personal/",
            "PATH_TO_REGISTER" => SITE_DIR."login/",
            "POSITION_FIXED" => "N",
            "SHOW_AUTHOR" => "N",
            "SHOW_EMPTY_VALUES" => "Y",
            "SHOW_NUM_PRODUCTS" => "Y",
            "SHOW_PERSONAL_LINK" => "N",
            "SHOW_PRODUCTS" => "N",
            "SHOW_TOTAL_PRICE" => "N",
            "CZ_AJAX" => "Y",
            "COMPOSITE_FRAME_MODE" => "N",
        )
    );
} elseif($request['action'] == 'list') {
    echo Basket::getList();
} elseif($request['action'] == 'delete') {
    $ID = $request["id"];
    echo Basket::Delete($ID);
} /*elseif($request['action'] == 'delay') {
    echo Basket::Delay($ID);
}*/ elseif($request['action'] == 'update') {
    $param = array(
        "ID" => $request["id"],
        "QUANTITY" => $request["quantity"],
    );
    echo Basket::Update($param);
}