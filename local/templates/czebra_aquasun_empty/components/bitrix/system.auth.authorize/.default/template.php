<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="auth-card login-site">
    <div class="auth-card__header title">
        <h1 class="auth-card__title">Авторизация</h1>
    </div>
    <div class="auth-card__message message">
    <?
    ShowMessage($arParams["~AUTH_RESULT"]);
    ShowMessage($arResult['ERROR_MESSAGE']);
    ?>
    </div>

	<form class="auth-form" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
		<?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>

		<div class="auth-form__field">
			<input class="bx-auth-input auth-form__input" type="text" placeholder="<?=GetMessage("AUTH_LOGIN")?>" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" data-cz-validated-type="data" data-cz-validated-group="group_auth" data-cz-validated-msg="* Необходимо заполнить поле" />
		</div>

		<div class="auth-form__field auth-form__field--password">
			<input class="bx-auth-input auth-form__input" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" data-cz-validated-type="data" data-cz-validated-group="group_auth" data-cz-validated-msg="* Необходимо заполнить поле"/>
<?if($arResult["SECURE_AUTH"]):?>
			<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
				<div class="bx-auth-secure-icon"></div>
			</span>
			<noscript>
			<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
				<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
			</span>
			</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?endif?>
		</div>

		<?if($arResult["CAPTCHA_CODE"]):?>
		<div class="auth-form__captcha capt-code">
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</div>
		<div class="auth-form__field field-capt-code">
			<input class="bx-auth-input auth-form__input" type="text" name="captcha_word" maxlength="50" value="" placeholder="Код с картинки" />
		</div>
		<?endif;?>

		<div class="auth-form__actions">
<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
			<label class="auth-form__remember" for="USER_REMEMBER">
				<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
				<span><?=GetMessage("AUTH_REMEMBER_ME")?></span>
			</label>
<?else:?>
			<span></span>
<?endif?>
			<input type="submit" class="auth-form__submit authorize-submit-cell" id="log" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
		</div>

<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
		<div class="auth-form__footer">
			<noindex>
				<a href="<?=str_replace('login.php','',$arResult["AUTH_FORGOT_PASSWORD_URL"])?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
			</noindex>
<?if($arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
			<noindex>
				<span class="auth-form__footer-sep">·</span>
				<a href="<?=str_replace('login.php','',$arResult["AUTH_REGISTER_URL"])?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a>
			</noindex>
<?endif?>
		</div>
<?endif?>

	</form>
</div>

<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
	array(
		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
		"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
		"AUTH_URL" => $arResult["AUTH_URL"],
		"POST" => $arResult["POST"],
		"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
		"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
		"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
	),
	$component,
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>
