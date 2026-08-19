<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="ci-title">Способ доставки</div>
<p>Самовывоз из магазина - бесплатно!</p>
<ul class="is-rlist delivery" style="display: none;">
    <?foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery):?>
    <?$clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true;submitForm();\"";?>
    <li class="is-radio">
        <p>
            <input type="radio" name="DELIVERY_ID" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" value="<?=$arDelivery["ID"]?>"<?if ($arDelivery["CHECKED"]=="Y"):?> checked="checked"<?endif?> <?=$clickHandler?> />
            <label for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" <?if ($arDelivery["CHECKED"]=="Y"):?> class="active"<?endif?>>
                <?if($arDelivery["ID"] != "56" && $arDelivery["ID"] != "58" && $arDelivery["ID"] != "51" && $arDelivery["ID"] != "59"):?>
                <?=$arDelivery["NAME"];?> <?if($arDelivery["PRICE_FORMATED"]):?> - <?=$arDelivery["PRICE_FORMATED"];?><?endif;?>
                <?else:?>
                <?=$arDelivery["NAME"];?> <?if($arDelivery["PRICE_FORMATED"]):?> - рассчитывает менеджер<?endif;?>
                <?endif?>
            </label>
            <?if($arDelivery["ID"] != "2" && $arDelivery["ID"] != "50"):?>
            <!--noindex-->
            <span><?=$arDelivery["DESCRIPTION"];?></span>
            <!--/noindex-->
            <?endif;?>
        </p>
    </li>
    <?endforeach?>
</ul>

