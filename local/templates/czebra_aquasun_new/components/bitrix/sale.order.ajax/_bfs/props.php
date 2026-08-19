<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties):?>

    <p><?=$arProperties["NAME"]?></p>
    <?
    //print_r($arProperties);
    $phone_tag = "";
    if ($arProperties["REQUIRED"] == "Y"  && $arProperties["IS_EMAIL"] == 'Y') {
        $validate = 'data-cz-validated-type="email" data-cz-validated-group="group_order" data-cz-validated-msg="* Необходимо заполнить поле '.$arProperties["NAME"].'"';
    } elseif($arProperties["REQUIRED"] == "Y") {
        $validate = 'data-cz-validated-type="data" data-cz-validated-group="group_order" data-cz-validated-msg="* Необходимо заполнить поле '.$arProperties["NAME"].'"';
    } else {
        $validate = '';
    }
    if ($arProperties["IS_PHONE"] == 'Y' ) {
        $phone_tag = "data-cz-telefon='Y'";
    }
    ?>
    <input class="itext" type="text" <?=$phone_tag?> value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" <?=$validate?>>

<?endforeach;?><?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>