<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="personal-profile">
	<form class="personal-profile__form" method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />

		<?ShowError($arResult["strProfileError"]);?>
		<?if ($arResult['DATA_SAVED'] == 'Y'):?>
		<div class="personal-profile__notice personal-profile__notice--success">Данные успешно сохранены</div>
		<?endif?>

		<div class="personal-profile__card">
			<h2 class="personal-profile__card-title">Контактные данные</h2>
			<div class="personal-profile__fields">
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-login">Логин</label>
					<input class="personal-profile__input" id="profile-login" type="text" name="LOGIN" maxlength="50" value="<?=htmlspecialcharsbx($arResult["arUser"]["LOGIN"])?>" autocomplete="username" />
				</div>
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-name">Имя и фамилия</label>
					<input class="personal-profile__input" id="profile-name" type="text" name="NAME" maxlength="50" value="<?=htmlspecialcharsbx($arResult["arUser"]["NAME"])?>" autocomplete="name" />
				</div>
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-phone">Телефон</label>
					<input class="personal-profile__input" id="profile-phone" type="tel" name="PERSONAL_PHONE" maxlength="255" value="<?=htmlspecialcharsbx($arResult["arUser"]["PERSONAL_PHONE"])?>" autocomplete="tel" inputmode="tel" />
				</div>
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-email">E-mail</label>
					<input class="personal-profile__input" id="profile-email" type="email" name="EMAIL" maxlength="255" value="<?=htmlspecialcharsbx($arResult["arUser"]["EMAIL"])?>" autocomplete="email" data-prime-alerts-email="1" />
				</div>
			</div>
			<div class="personal-profile__actions">
				<button type="submit" id="data-base" name="save" class="personal-profile__submit" value="Y">Сохранить данные</button>
			</div>
		</div>

		<div class="personal-profile__card">
			<h2 class="personal-profile__card-title">Изменение пароля</h2>
			<div class="personal-profile__fields">
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-password">Новый пароль</label>
					<input class="personal-profile__input" id="profile-password" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="new-password" />
				</div>
				<div class="personal-profile__field">
					<label class="personal-profile__label" for="profile-password-confirm">Подтверждение пароля</label>
					<input class="personal-profile__input" id="profile-password-confirm" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="new-password" />
				</div>
			</div>
			<div class="personal-profile__actions">
				<button type="submit" name="save" class="personal-profile__submit personal-profile__submit--secondary" value="Y">Изменить пароль</button>
			</div>
		</div>
	</form>
</div>
<script>
$(function(){
	$('[name="PERSONAL_PHONE"]').mask("+7(999) 999-99-99");

	$(document).on("keyup change", "[name='LOGIN'], [name='NAME'], [name='EMAIL']", function(){ ValidClear(this); });

	$("#data-base").click(function(){
		var flagLogin = ValidName($("[name='LOGIN']"));
		var flagFIO = ValidName($("[name='NAME']"));
		var flagEmail = ValidEmail($("[name='EMAIL']"));

		if (!flagLogin) {
			$("[name='LOGIN']").focus();
			showMessage($("[name='LOGIN']"), 'Введите корректно логин');
		}
		if (!flagFIO) {
			$("[name='NAME']").focus();
			showMessage($("[name='NAME']"), 'Введите корректно ФИО');
		}
		if (!flagEmail) {
			$("[name='EMAIL']").focus();
			showMessage($("[name='EMAIL']"), 'Введите корректно ваш Email');
		}

		return flagLogin && flagFIO && flagEmail;
	});
});

function ValidName(el){
	var lenVLngth = $(el).val().length;
	return lenVLngth > 2;
}
function showMessage(inps, msg){
	inps.closest('.personal-profile__field').find('.cz-wrap-error').remove();
	inps.addClass('cz-error');
	inps.closest('.personal-profile__field').append('<div class="cz-wrap-error"><p class="cz-input-error">* '+ msg +'</p></div>');
	inps.focus();
}
function ValidEmail(el){
	var emailV = $(el).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
	return filter.test(emailV);
}
function ValidClear(el){
	$(el).removeClass('cz-error');
	$(el).closest('.personal-profile__field').find('.cz-wrap-error').remove();
}
</script>
