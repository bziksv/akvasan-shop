<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>

<div class="special-slider bx3">
    <div class="bxslider">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <div class="slide">
                
                <div class="img-slide">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"  title="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></a>
                </div>
                
                <a href="" data-compare-action="add" data-compare-id="<?=$arItem["ID"]?>" class="arrow-slide"></a>
                
                <div class="name-product-slide">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem['NAME']?></a>
                    <?/*<span class="size-product">9.99х9.99.</span>*/?>
                    <span class="manufacturer-country">
                        <img src="/upload/template_new/flag_<?=Cutil::translit($arItem["PROPERTIES"]["STRANA"]["VALUE"],"ru",array())?>.png" alt="<?=$arItem["PROPERTIES"]["STRANA"]["VALUE"]?>"> <a href="/country/<?=Cutil::translit($arItem["PROPERTIES"]["STRANA"]["VALUE"],"ru",array())?>/"><?=$arItem["PROPERTIES"]["STRANA"]["VALUE"]?></a>
                    </span>
                </div>
                
                <div class="price-product-slide">
                    <span class="price"><?=str_replace("руб.","р.",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?></span>
                    <a href="" class="add-to-cart" data-cz-buy="<?=$arItem["ID"]?>" data-cz="addtocart">В корзину</a>
                </div>
                
            </div>
        <?endforeach;?>
    </div>
</div>
    