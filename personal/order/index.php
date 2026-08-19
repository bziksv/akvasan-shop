<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<?
global $USER;
if (!$USER->IsAuthorized()) {
	LocalRedirect("/login/?backurl=" . urlencode($APPLICATION->GetCurPageParam()));
}
?>
<div class="container-main personal-section personal-section--orders">
	<?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/personal_back.php'; ?>
	<div class="title"><h1>История заказов</h1></div>

<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order", "", array(
	"SEF_MODE" => "Y",
	"SEF_FOLDER" => "/personal/order/",
	"ORDERS_PER_PAGE" => "10",
	"PATH_TO_PAYMENT" => "/personal/order/payment/",
	"PATH_TO_BASKET" => "/personal/cart/",
	"SET_TITLE" => "N",
	"SAVE_IN_SESSION" => "N",
	"NAV_TEMPLATE" => "arrows",
	"SEF_URL_TEMPLATES" => array(
		"list" => "index.php",
		"detail" => "detail/#ID#/",
		"cancel" => "cancel/#ID#/",
	),
	"SHOW_ACCOUNT_NUMBER" => "Y"
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
