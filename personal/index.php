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
<div class="container-main personal-cabinet">
	<div class="title"><h1>Личный кабинет</h1></div>
	<p class="personal-cabinet__intro">В личном кабинете вы можете проверить текущее состояние корзины, ход выполнения заказов, просмотреть или изменить личную информацию.</p>

	<nav class="personal-cabinet__nav" aria-label="Разделы личного кабинета">
		<a href="<?=SITE_DIR?>personal/cart/" class="personal-cabinet__item">
			<span class="personal-cabinet__icon" aria-hidden="true"><i class="fas fa-shopping-cart"></i></span>
			<span class="personal-cabinet__content">
				<span class="personal-cabinet__label">Корзина</span>
				<span class="personal-cabinet__desc">Посмотреть содержимое корзины</span>
			</span>
			<span class="personal-cabinet__arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
		</a>

		<a href="<?=SITE_DIR?>personal/order/" class="personal-cabinet__item">
			<span class="personal-cabinet__icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></span>
			<span class="personal-cabinet__content">
				<span class="personal-cabinet__label">История заказов</span>
				<span class="personal-cabinet__desc">Статус и детали ваших заказов</span>
			</span>
			<span class="personal-cabinet__arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
		</a>

		<a href="<?=SITE_DIR?>personal/profile/" class="personal-cabinet__item">
			<span class="personal-cabinet__icon" aria-hidden="true"><i class="fas fa-user-edit"></i></span>
			<span class="personal-cabinet__content">
				<span class="personal-cabinet__label">Личные данные</span>
				<span class="personal-cabinet__desc">Изменить контакты и профиль</span>
			</span>
			<span class="personal-cabinet__arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
		</a>

		<a href="<?=SITE_DIR?>?logout=yes" class="personal-cabinet__item personal-cabinet__item--logout">
			<span class="personal-cabinet__icon" aria-hidden="true"><i class="fas fa-sign-out-alt"></i></span>
			<span class="personal-cabinet__content">
				<span class="personal-cabinet__label">Выход</span>
				<span class="personal-cabinet__desc">Выйти из профиля</span>
			</span>
			<span class="personal-cabinet__arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
		</a>
	</nav>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
