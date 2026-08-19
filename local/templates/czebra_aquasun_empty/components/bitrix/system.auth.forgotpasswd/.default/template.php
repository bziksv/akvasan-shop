<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="auth-card auth-card--forgot login-site">
    <div class="auth-card__header title">
        <h1 class="auth-card__title">Восстановление пароля</h1>
    </div>
    <div class="auth-card__message message">
    <?ShowMessage($arParams["~AUTH_RESULT"]);?>
    </div>

<form class="auth-form auth-form--forgot" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">

	<p class="auth-card__hint"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></p>

	<div class="auth-form__field">
		<input class="auth-form__input" type="text" name="USER_EMAIL" maxlength="255" placeholder="<?=GetMessage("AUTH_EMAIL")?>" data-cz-validated-type="data" data-cz-validated-group="group_forgpassw" data-cz-validated-msg="* Необходимо заполнить поле" />
	</div>

<?if($arResult["USE_CAPTCHA"]):?>
	<div class="auth-form__captcha">
		<div class="auth-form__captcha-image">
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</div>
		<div class="auth-form__field auth-form__field--captcha">
			<label class="auth-form__label"><?echo GetMessage("system_auth_captcha")?></label>
			<input class="auth-form__input" type="text" name="captcha_word" maxlength="50" value="" />
		</div>
	</div>
<?endif?>

	<div class="auth-form__actions auth-form__actions--center">
		<input type="submit" class="auth-form__submit" id="send-passw" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
	</div>

	<div class="auth-form__footer">
		<a href="<?=str_replace('login.php','',$arResult["AUTH_AUTH_URL"])?>"><b><?=GetMessage("AUTH_AUTH")?></b></a>
	</div>
</form>
<script type="text/javascript">
if (document.bform && document.bform.USER_EMAIL) {
	document.bform.USER_EMAIL.focus();
}
</script>
</div>
