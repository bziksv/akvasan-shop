<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<li class="is-radio is-radio-inline" style="display: none">
    <?foreach($arResult["PERSON_TYPE"] as $artType):?>
    <?$chk = $arResult["USER_VALS"]["PERSON_TYPE_ID"] == $artType["ID"] ? ' checked="checked"': '';?>
    <p>
        <input  id="PERSON_TYPE_<?=$artType["ID"]?>" name="PERSON_TYPE" type="radio" name="person" value="<?=$artType["ID"]?>"<?=$chk?> onClick="submitForm()" />
        <label for="PERSON_TYPE_<?=$artType["ID"]?>"><?=$artType["NAME"]?></label>
    </p>
    <?endforeach?>
    <p></p>
</li>
<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>" />
