<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

$primeRegEmailExistsNotice = '';
if (Loader::includeModule('prime.alerts')) {
	$primeRegEmailExistsNotice = \Prime\Alerts\EmailLookup::getExistsNoticeHtml();
}
?>
<div class="auth-card auth-card--registration login-site">
    <div class="auth-card__header title">
        <h1 class="auth-card__title">Регистрация</h1>
    </div>
    <div class="auth-card__message message">
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
    </div>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p class="auth-card__hint"><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<noindex>
<form class="auth-form auth-form--registration" method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />
	<input type="hidden" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" />

	<div class="auth-form__field">
		<input class="auth-form__input" type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" placeholder="<?=GetMessage("AUTH_NAME")?>" data-cz-validated-type="data" data-cz-validated-group="group_registration" data-cz-validated-msg="* Укажите имя" />
	</div>

	<div class="auth-form__field">
		<input class="auth-form__input" type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" placeholder="<?=GetMessage("AUTH_LAST_NAME")?>" data-cz-validated-type="data" data-cz-validated-group="group_registration" data-cz-validated-msg="* Укажите фамилию" />
	</div>

	<div class="auth-form__field auth-form__field--password">
		<input class="auth-form__input" type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_PASSWORD_REQ")?>" autocomplete="off" data-cz-validated-type="data" data-cz-validated-group="group_registration" data-cz-validated-msg="* Укажите пароль" />
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

	<div class="auth-form__field">
		<input class="auth-form__input" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_CONFIRM")?>" autocomplete="off" data-cz-validated-type="data" data-cz-validated-group="group_registration" data-cz-validated-msg="* Подтвердите пароль" />
	</div>

	<div class="auth-form__field auth-form__field--email">
		<input class="auth-form__input" type="email" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" placeholder="<?=GetMessage("AUTH_EMAIL")?>" autocomplete="email" data-cz-validated-type="email" data-cz-validated-group="group_registration" data-cz-validated-msg="* Укажите корректный e-mail" data-prime-alerts-email="1" />
	</div>

	<div class="auth-form__field auth-form__field--phone">
		<input class="auth-form__input" type="tel" name="PERSONAL_PHONE" maxlength="16" value="<?=htmlspecialcharsbx((string)($_REQUEST['PERSONAL_PHONE'] ?? ''))?>" placeholder="Телефон" autocomplete="tel" inputmode="tel" data-cz-validated-type="phone" data-cz-validated-group="group_registration" data-cz-validated-msg="* Укажите телефон в формате +7-999-999-99-99" />
	</div>

<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<div class="auth-form__section">
		<div class="auth-form__section-title"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></div>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
		<div class="auth-form__field auth-form__field--property">
			<label class="auth-form__label"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?><?=$arUserField["EDIT_FORM_LABEL"]?></label>
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?>
		</div>
	<?endforeach;?>
	</div>
<?endif;?>

<?if ($arResult["USE_CAPTCHA"] == "Y"):?>
	<div class="auth-form__captcha">
		<div class="auth-form__captcha-title"><?=GetMessage("CAPTCHA_REGF_TITLE")?></div>
		<div class="auth-form__captcha-image">
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</div>
		<div class="auth-form__field auth-form__field--captcha">
			<input class="auth-form__input" type="text" name="captcha_word" maxlength="50" value="" placeholder="* Введите слово с картинке" autocomplete="off" data-cz-validated-type="data" data-cz-validated-group="group_registration" data-cz-validated-msg="* Введите слово с картинки" />
		</div>
	</div>
<?endif;?>

	<div class="order-consent auth-form__consent">
		<input type="checkbox" id="reg_personal_consent" name="REGISTER_PERSONAL_CONSENT" value="Y" data-cz-validated-type="checkbox" data-cz-validated-group="group_registration" data-cz-validated-msg="* Необходимо дать согласие на обработку персональных данных" />
		<label for="reg_personal_consent" class="order-consent__text">Я даю согласие на обработку персональных данных в соответствии с <a target="_blank" href="/legal/personal-data/">Политикой обработки персональных данных</a>.</label>
	</div>

	<div class="auth-form__actions auth-form__actions--center">
		<input type="submit" class="auth-form__submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
	</div>

	<div class="auth-form__footer">
		<a href="<?=str_replace('login.php','',$arResult["AUTH_AUTH_URL"])?>" rel="nofollow"><?=GetMessage("AUTH_AUTH")?></a>
	</div>

</form>
</noindex>
<script type="text/javascript">
if (document.bform && document.bform.USER_NAME) {
	document.bform.USER_NAME.focus();
}
if (window.jQuery && jQuery.fn.mask) {
	jQuery('[name="PERSONAL_PHONE"]').mask('+7-999-999-99-99');
}
(function () {
	var NOTICE_HTML = <?= Json::encode($primeRegEmailExistsNotice) ?>;
	var CHECK_URL = '/local/modules/prime.alerts/ajax/check_email.php';

	function primeAlertsBindRegistration() {
		var form = document.bform;
		if (!form || !form.USER_EMAIL) return;

		if (typeof window.primeAlertsCheckRegistrationEmail === 'function') {
			window.primeAlertsCheckRegistrationEmail(form.USER_EMAIL);
		}

		if (!NOTICE_HTML || form.USER_EMAIL.getAttribute('data-prime-reg-dup-bound') === '1') {
			return;
		}
		form.USER_EMAIL.setAttribute('data-prime-reg-dup-bound', '1');

		var cache = Object.create(null);
		var timers = Object.create(null);

		function sessid() {
			if (window.PRIME_ALERTS && window.PRIME_ALERTS.sessid) {
				return window.PRIME_ALERTS.sessid;
			}
			return (window.BX && BX.bitrix_sessid && BX.bitrix_sessid()) || '';
		}

		function looksComplete(email) {
			return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test(String(email || '').trim());
		}

		function emailAnchor(inp) {
			return inp.closest('.auth-form__field--email') || inp.closest('.auth-form__field') || inp.parentNode;
		}

		function hideDupBox(anchor) {
			if (!anchor) return;
			var box = anchor.querySelector(':scope > .prime-alerts-live-notice[data-kind="duplicate"]');
			if (box && box.parentNode) box.parentNode.removeChild(box);
		}

		function showDupBox(anchor) {
			if (!anchor || !NOTICE_HTML) return;
			hideDupBox(anchor);
			var box = document.createElement('div');
			box.className = 'prime-alerts-live-notice is-visible';
			box.setAttribute('aria-live', 'polite');
			box.setAttribute('data-kind', 'duplicate');
			box.innerHTML = NOTICE_HTML;
			anchor.appendChild(box);
		}

		function checkRegEmail(inp) {
			var email = String(inp.value || '').trim();
			var anchor = emailAnchor(inp);
			if (!looksComplete(email)) {
				hideDupBox(anchor);
				return;
			}
			if (cache[email] !== undefined) {
				if (cache[email]) showDupBox(anchor);
				else hideDupBox(anchor);
				return;
			}
			clearTimeout(timers[inp]);
			timers[inp] = setTimeout(function () {
				fetch(CHECK_URL, {
					method: 'POST',
					credentials: 'same-origin',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
						'X-Requested-With': 'XMLHttpRequest'
					},
					body: 'sessid=' + encodeURIComponent(sessid()) + '&email=' + encodeURIComponent(email)
				}).then(function (r) { return r.json(); }).then(function (data) {
					cache[email] = !!(data && data.ok && data.exists);
					if (String(inp.value || '').trim() === email) {
						checkRegEmail(inp);
					}
				}).catch(function () {});
			}, 400);
		}

		['input', 'change', 'blur', 'keyup', 'paste'].forEach(function (ev) {
			form.USER_EMAIL.addEventListener(ev, function () {
				if (form.USER_LOGIN) {
					form.USER_LOGIN.value = form.USER_EMAIL.value;
				}
				checkRegEmail(form.USER_EMAIL);
				if (typeof window.primeAlertsCheckRegistrationEmail === 'function') {
					window.primeAlertsCheckRegistrationEmail(form.USER_EMAIL);
				}
			});
		});
		checkRegEmail(form.USER_EMAIL);
	}

	primeAlertsBindRegistration();
})();
</script>

<?endif?>
</div>
