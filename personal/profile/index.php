<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личные данные");
?>
<?
global $USER;
if (!$USER->IsAuthorized()) {
	LocalRedirect("/login/?backurl=" . urlencode($APPLICATION->GetCurPageParam()));
}
?>
<div class="container-main personal-section personal-section--profile">
	<?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/personal_back.php'; ?>
	<div class="title"><h1>Личные данные</h1></div>
	<p class="personal-section__intro">Измените контактные данные или пароль для входа на сайт.</p>

<?$APPLICATION->IncludeComponent("bitrix:main.profile", "short", Array(
	"SET_TITLE" => "N",
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
