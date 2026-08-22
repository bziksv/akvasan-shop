<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 */

$validationGroup = "FORM_CALLBACK_group";

$fieldsMeta = [
    "form_text_1" => [
        "label" => "Имя",
        "placeholder" => "Имя",
        "type" => "data",
        "msg" => "* Укажите имя",
        "required" => true,
    ],
    "form_text_2" => [
        "label" => "Телефон",
        "placeholder" => "Телефон",
        "type" => "phone",
        "msg" => "* Укажите телефон в формате +7-999-999-99-99",
        "required" => true,
    ],
];

$formTitle = "Заказать звонок";
$formIntro = "Заполните форму и наш менеджер в скором времени свяжется с вами.";

if (!empty($arResult["FORM_TITLE"]) && $arResult["FORM_TITLE"] !== "callback") {
    $formTitle = $arResult["FORM_TITLE"];
}

if (!empty($arResult["FORM_DESCRIPTION"])) {
    $formIntro = $arResult["FORM_DESCRIPTION"];
}

if ($arResult["isFormErrors"] === "Y") {
    echo $arResult["FORM_ERRORS_TEXT"];
}

if (!empty($arResult["FORM_NOTE"])) {
    echo $arResult["FORM_NOTE"];
}

if ($arResult["isFormNote"] === "Y") {
    return;
}
?>
<?=$arResult["FORM_HEADER"]?>

<div class="callback-form">
    <h2 class="modal-title callback-form__title"><?=htmlspecialcharsbx($formTitle)?></h2>
    <p class="callback-form__intro"><?=$formIntro?></p>

    <?php foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):
        if (($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] ?? "") === "hidden") {
            echo $arQuestion["HTML_CODE"];
            continue;
        }

        $inputName = "";
        if (preg_match('/name="([^"]+)"/', $arQuestion["HTML_CODE"], $matches)) {
            $inputName = $matches[1];
        }

        $meta = $fieldsMeta[$inputName] ?? [
            "label" => trim(strip_tags($arQuestion["CAPTION"])),
            "placeholder" => trim(strip_tags($arQuestion["CAPTION"])),
            "type" => "data",
            "msg" => "* Необходимо заполнить поле",
            "required" => ($arQuestion["REQUIRED"] === "Y"),
        ];

        if ($meta["label"] === "") {
            $meta["label"] = "Поле";
        }

        $isRequired = !empty($meta["required"]);
        $inputId = "callback_" . preg_replace('/[^a-zA-Z0-9_\-]/', "_", $inputName);
        ?>
        <div class="callback-form__field auth-form__field">
            <label class="callback-form__label auth-form__label" for="<?=$inputId?>">
                <?=htmlspecialcharsbx($meta["label"])?><?php if ($isRequired): ?><span class="callback-form__required">*</span><?php endif; ?>
            </label>
            <input
                type="text"
                class="callback-form__input auth-form__input"
                id="<?=$inputId?>"
                name="<?=htmlspecialcharsbx($inputName)?>"
                value="<?=htmlspecialcharsbx($_REQUEST[$inputName] ?? "")?>"
                placeholder="<?=htmlspecialcharsbx($meta["placeholder"])?>"
                autocomplete="<?=$inputName === "form_text_2" ? "tel" : "name"?>"
                <?php if ($isRequired): ?>
                data-cz-validated-type="<?=htmlspecialcharsbx($meta["type"])?>"
                data-cz-validated-group="<?=$validationGroup?>"
                data-cz-validated-msg="<?=htmlspecialcharsbx($meta["msg"])?>"
                <?php endif; ?>
            />
        </div>
    <?php endforeach; ?>

    <?php if ($arResult["isUseCaptcha"] === "Y"): ?>
        <div class="callback-form__field auth-form__field callback-form__field--captcha">
            <label class="callback-form__label auth-form__label" for="callback_captcha_word">
                <?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><span class="callback-form__required">*</span>
            </label>
            <div class="callback-form__captcha-image">
                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" width="180" height="40" alt="" />
            </div>
            <input
                type="text"
                class="callback-form__input auth-form__input"
                id="callback_captcha_word"
                name="captcha_word"
                maxlength="50"
                value=""
                autocomplete="off"
                placeholder="* Введите слово с картинки"
                data-cz-validated-type="data"
                data-cz-validated-group="<?=$validationGroup?>"
                data-cz-validated-msg="* Введите слово с картинки"
            />
        </div>
    <?php endif; ?>

    <div class="order-consent callback-form__consent">
        <input
            type="checkbox"
            id="callback_personal_consent"
            name="CALLBACK_PERSONAL_CONSENT"
            value="Y"
            data-cz-validated-type="checkbox"
            data-cz-validated-group="<?=$validationGroup?>"
            data-cz-validated-msg="* Необходимо дать согласие на обработку персональных данных"
        />
        <label for="callback_personal_consent" class="order-consent__text">
            Я даю согласие на обработку персональных данных в соответствии с
            <a target="_blank" href="/legal/personal-data/">Политикой обработки персональных данных</a>.
        </label>
    </div>

    <div class="callback-form__actions">
        <input
            <?=(intval($arResult["F_RIGHT"]) < 10 ? 'disabled="disabled"' : "")?>
            type="submit"
            name="web_form_submit"
            id="cz1_sibmit"
            class="callback-form__submit btn-default"
            value="Заказать"
        />
    </div>
</div>

<?=$arResult["FORM_FOOTER"]?>

<script>
(function () {
    if (window.jQuery && jQuery.fn.mask) {
        jQuery("#cz_form input[name='form_text_2']").mask("+7-999-999-99-99");
    }
    if (typeof cz_validated !== "undefined") {
        cz_validated.bind("<?=$validationGroup?>");
        cz_validated.runBtn("cz1_sibmit", "<?=$validationGroup?>");
    }
})();
</script>
