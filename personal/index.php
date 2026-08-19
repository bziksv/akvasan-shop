<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Личный кабинет");
$APPLICATION->SetTitle("Персональный раздел");
?>
<?
global $USER;
if (!$USER->IsAuthorized()) {
	LocalRedirect("/login/?backurl=" . urlencode($APPLICATION->GetCurPageParam()));
}
?>
<div class="container-main">
    <div class="title"><h1>Личный кабинет</h1></div>
	<p>В личном кабинете вы можете проверить текущее состояние корзины, ход выполнения заказов, просмотреть или изменить личную информацию.</p>
    <br/>
        <a href="cart/">Посмотреть содержимое корзины</a><br/>
	    <a href="order/">История заказов</a><br/>
        <a href="profile/">Изменить личные данные</a><br/><br/>
        <a href="/?logout=yes">Выход из профиля</a><br/>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
