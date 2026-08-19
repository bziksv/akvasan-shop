<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery):?>
    <?$clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true;submitForm();\"";?>
    
    <input type="radio" name="DELIVERY_ID" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" value="<?=$arDelivery["ID"]?>"<?if ($arDelivery["CHECKED"]=="Y"):?> checked="checked"<?endif?> <?=$clickHandler?> />
    <label for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" <?if ($arDelivery["CHECKED"]=="Y"):?> class="active"<?endif?>>
        <?=$arDelivery["NAME"];?> <?if($arDelivery["PRICE_FORMATED"]):?> - <?=$arDelivery["PRICE_FORMATED"];?><?endif;?>
    </label>    
<?endforeach?>
