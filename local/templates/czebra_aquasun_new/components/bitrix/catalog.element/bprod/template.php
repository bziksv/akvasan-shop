<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<div class="card-container"><div class="row">
    <div class="title-slider hidden-lg hidden-md">
        <?=$arResult["NAME"]?>
    </div>
    <?require(realpath(dirname(__FILE__)).'/photo.php')?>

    <div class="info-card col-lg-6 col-md-6 col-xs-12">
		<? if($arResult['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE'] || $arResult['PROPERTIES']['SKIDKA_PRI_OPLATE_V_MAGAZINE']['VALUE']): ?>
		<div class="alert alert-info text-center" role="alert">
			<? if($arResult['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE']): ?>
				<strong>На этот товар в корзине Вас ждёт скидка <?=$arResult['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE']?>%!</strong>
			<? else: ?>
				<strong>Скидка при покупке в розничных магазинах: <?=$arResult['PROPERTIES']['SKIDKA_PRI_OPLATE_V_MAGAZINE']['VALUE']?>%</strong>
			<? endif; ?>
		</div>
		<? endif; ?>
		<h1 class="title-product"><? if($arResult["PROPERTIES"]["SITE_NAME"]["VALUE"]){ echo $arResult["PROPERTIES"]["SITE_NAME"]["VALUE"]; }else{ echo $arResult["NAME"]; } ?></h1>
        <?if($arResult["ITEM_PRICES"][$arResult["ITEM_PRICE_SELECTED"]]["PRICE"] < 10):?>
            <div class="product-not-available">Нет в наличии <?/*a href="" id="to-order-not-available">Заказать</a*/?></div>
        <?else:?>
        <div class="price-product">
            <span><?=str_replace(" руб.","р.",$arResult["ITEM_PRICES"][$arResult["ITEM_PRICE_SELECTED"]]["PRINT_BASE_PRICE"])?></span>
            
            <a href="" class="add-to-cart cart-icon" data-cz="addtocart" data-cz-buy="<?=$arResult["ID"]?>">В корзину</a>
            
            <a href="" data-compare-action="add" data-compare-id="<?=$arResult["ID"]?>" class="arrow-slide"></a>
        </div>
        <?endif;?>
<?
if ($USER->IsAdmin()){
	?>
	<div class="container-characteristics">
		<?
		foreach($arResult['STORES'] as $stores):
		?>
			<div class="characteristic">
				<div class="name-characteristic"><b><?=$stores["TITLE"]?></b></div>
				<div class="value-characteristic"><?=$stores["AMOUNT"]?></div>
			</div>
		<?
		endforeach;
		?>
	</div>
<?
}
?>
        <?require(realpath(dirname(__FILE__)).'/properties.php')?>
    </div>
</div></div>
<? if(trim($arResult["DETAIL_TEXT"])): ?>
<div class="container-novelty" style="padding: 21px;">
	<div class="title-main">Описание товара <?=strtolower($arResult["NAME"])?></div>
	<p><?=$arResult["DETAIL_TEXT"]?> </p>
</div>
<? endif; ?>
<input type='hidden' id='base_price_complect' value='<?=$arResult["ITEM_PRICES"][$arResult["ITEM_PRICE_SELECTED"]]['BASE_PRICE']?>' />
<input type='hidden' id='name_form_to_order' value='<?=$arResult["NAME"]?>'>