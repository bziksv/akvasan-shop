<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

$primeRegEmailExistsNotice = '';
if (Loader::includeModule('prime.alerts')) {
	$primeRegEmailExistsNotice = \Prime\Alerts\EmailLookup::getExistsNoticeHtml();
}
?>
<div class="container-main login-site col-lg-12 col-md-12 col-xs-12">
    <div class="title"><h1>Регистрация</h1></div> 
    <br/>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<noindex>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
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

<table class="data-table bx-registration-table bx-auth-table">
	<tbody>
		<tr>
			<td></td>
			<td><input type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" placeholder="<?=GetMessage("AUTH_NAME")?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" placeholder="<?=GetMessage("AUTH_LAST_NAME")?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" placeholder="<?=GetMessage("AUTH_LOGIN_MIN")?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_PASSWORD_REQ")?>" autocomplete="off" />
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
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_CONFIRM")?>" autocomplete="off" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" placeholder="<?=GetMessage("AUTH_EMAIL")?>" autocomplete="email" /></td>
		</tr>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<tr><td><?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;
		?><?=$arUserField["EDIT_FORM_LABEL"]?>:</td><td>
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
		<tr>
			<td colspan="2"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</td>
			<td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
		<?
	}
	/* CAPTCHA */
	?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td><input type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" /></td>
		</tr>
	</tfoot>
</table>

<p class="priv-policy">Нажимая кнопку «Регистрация», вы даёте согласие на обработку персональных данных в соответствии с нашей <a target="_blank" href="/legal/personal-data/">Политикой обработки персональных данных</a>.</p>




<p>
<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a>
</p>

</form>
</noindex>
<script type="text/javascript">
if (document.bform && document.bform.USER_NAME) {
	document.bform.USER_NAME.focus();
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
			return inp.closest('td') || inp.parentNode;
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

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', primeAlertsBindRegistration);
	} else {
		primeAlertsBindRegistration();
	}
})();
</script>

<?endif?>
</div>