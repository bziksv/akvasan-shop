<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties):
 if($arProperties["CODE"] == "SHOP"){
     continue;
 }
?>
<li>
    <?
    $phone_tag = "";
    $inputType = "text";
    $emailAttrs = "";
    if ($arProperties["REQUIRED"] == "Y"  && $arProperties["IS_EMAIL"] == 'Y') {
        $validate = 'data-cz-validated-type="email" data-cz-validated-group="group_order" data-cz-validated-msg="* Необходимо заполнить поле '.$arProperties["NAME"].'"';
        $inputType = "email";
        $emailAttrs = 'autocomplete="email" data-prime-alerts-email="1"';
    } elseif($arProperties["REQUIRED"] == "Y") {
        $validate = 'data-cz-validated-type="data" data-cz-validated-group="group_order" data-cz-validated-msg="* Необходимо заполнить поле '.$arProperties["NAME"].'"';
    } else {
        $validate = '';
    }
    if ($arProperties["IS_EMAIL"] == 'Y' && $inputType !== 'email') {
        $inputType = "email";
        $emailAttrs = 'autocomplete="email" data-prime-alerts-email="1"';
    }
    if ($arProperties["IS_PHONE"] == 'Y' ) {
        $phone_tag = "data-cz-telefon='Y'";
    }
    ?>
    <input placeholder="<?=$arProperties["NAME"]?>" class="itext" type="<?=$inputType?>" <?=$emailAttrs?> <?=$phone_tag?> value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" <?=$validate?>>
</li>
<?endforeach;?>

<?global $USER;?>
<?
if($USER->isAdmin()){
    //print_r($arResult["ORDER_PROP"]["USER_PROPS_Y"]);
}
?>
<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties):?>
    <?if($arProperties["CODE"] == "SHOP"):?>
        <!--<?=$arProperties["NAME"]?>-->
        <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="<?=$arProperties["VALUE"]?>" />
    <?endif;?>
<?endforeach?>