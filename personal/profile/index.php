<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки пользователя");
?>
<?
global $USER;
if (!$USER->IsAuthorized()) {
	LocalRedirect("/login/?backurl=" . urlencode($APPLICATION->GetCurPageParam()));
}
?>
<?$APPLICATION->IncludeComponent("bitrix:main.profile", "short", Array(
	"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>