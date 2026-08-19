<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?foreach ($arResult["ITEMS"] as $arItem) :?>
<div class="item-workarea col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="item">
        <?if($arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PERCENT"] > 0):?>
            <div class="disc"><span>-<?=$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PERCENT"]?>%</span></div>
        <?endif?>
        <?if (is_array($arItem["PREVIEW_PICTURE"])) :?>
        <div class="item-img"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" class="img-responsive"></a></div>
        <?else:?>
        <div class="item-img"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="/upload/template/no_photo_200.png" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" class="img-responsive"></a></div>
        <?endif?>
        <div class="item-description"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dotdotdot" title="<?=$arItem['NAME']?>"><?=$arItem["NAME"]?></a></div>
        <span class="price"><?=str_replace(" руб.","",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?></span><span class="rubl">i</span>
        <?if(strlen($arItem["PROPERTIES"]["STRANA_1"]["VALUE"]) > 0):?>
        <img src="/upload/template/flag_<?=Cutil::translit($arItem["PROPERTIES"]["STRANA_1"]["VALUE"],"ru",array())?>.png" class="flagger">
        <?endif?>
        <a href="" class="add-to-cart" cz-data-buy="<?=$arItem["ID"]?>" cz-data="addtocart">в корзину</a>
    </div>
    <div class="hidden-text-workarea"><div class="hidden-text">
        <?
        $i = 0;
        foreach ($arItem["PROPERTIES"] as $pid => $arProperty) :?>
            <?if(strlen($arProperty["VALUE"]) > 0 && !in_array($pid, \Czebra\Base\Consts::STOP_PROP)):?>
            <p><?=$arProperty["NAME"]?>: <?=$arProperty["VALUE"];?></p>
                <?
                $i++;

                if ($i == 4) {
                   break;
                }
                ?>
            <?endif?>
        <?endforeach;?>
    </div></div>
</div>
<?endforeach?>
<?if ($arParams['AJAX'] == 'Y') :?>
<div id="wrap-pager-ajax" style="display: none;"><?=$arResult["NAV_STRING"];?></div>
<?endif?>
