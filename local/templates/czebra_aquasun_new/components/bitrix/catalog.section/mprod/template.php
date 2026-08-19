<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>

<?/*<div class="row">
<?foreach ($arResult['ITEMS'] as $arItem):?>
<div class="new-item col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="new-product-wrapper"><div class="new-product1">
    <?if($arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PERCENT"] > 0):?>
            <div class="disc"><span>-<?=$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PERCENT"]?>%</span></div>
    <?endif?>
<?if (is_array($arItem["PREVIEW_PICTURE"])) :?>
    <div class="img-product"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem['NAME']?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" class="img-responsive" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></a></div>
<?else:?>
    <div class="img-product"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="/upload/template/no_photo_200.png" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" class="img-responsive"></a></div>
<?endif?>
    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="description-new-product dotdotdot" title="<?=$arItem['NAME']?>"><?=$arItem['NAME']?></a>
    <span class="price"><?=str_replace(" руб.","",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?></span><span class="rubl">a</span>
    <?if(strlen($arItem["PROPERTIES"]["STRANA_1"]["VALUE"]) > 0):?>
        <img src="/upload/template/flag_<?=Cutil::translit($arItem["PROPERTIES"]["STRANA_1"]["VALUE"],"ru",array())?>.png" class="flagger">
    <?endif?>
    <a href="" class="add-to-cart" cz-data-buy="<?=$arItem["ID"]?>" cz-data="addtocart">в корзину</a>
</div></div></div>
<?endforeach?>
</div>
*/?>

<div class="container-novelty">
    <div class="title-main">
		<? if(isset($arParams["MAIN_TITLE"])): ?>
			<?=$arParams["MAIN_TITLE"];?>
		<? else: ?>
			<?if(CSite::InDir('/catalog/product/')):?>
				<?=GetMessage('RELATED_PRODUCTS');?>
			<?else:?>
				<?=GetMessage('NOVELTIES');?>
			<?endif;?>
		<?endif;?>
    </div>
    
    <div class="novelty-slider"><div class="bxslider">
            <?foreach($arResult['ITEMS'] as $arItem):?>
                <div class="slide">
                            
                    <div class="img-slide">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></a>
                    </div>

                    <a href="" data-compare-action="add" data-compare-id="<?=$arItem["ID"]?>" class="arrow-slide"></a>

                    <div class="name-product-slide">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem['NAME']?></a>
                        <!-- <span class="size-product">170х60 см.</span>
                        <span class="manufacturer-country">
                            <img src="img/flages.png" alt="Испания"> <span>Испания</span>
                        </span> -->
                    </div>

                    <div class="price-product-slide">
                        <span class="price"><?=str_replace("руб.","р.",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?></span>
                        <a href="" class="add-to-cart" data-cz-buy="<?=$arItem["ID"]?>" data-cz="addtocart">В корзину</a>
                    </div>

                </div>
            <?endforeach?>
    </div></div>
</div>


