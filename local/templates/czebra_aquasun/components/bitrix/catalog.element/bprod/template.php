<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<div class="product-info col-lg-12 col-md-12 col-xs-12"><div class="row">
    <div class="product-title-mobile hidden-lg hidden-md"><?=$arResult["NAME"]?></div>
    <?require(realpath(dirname(__FILE__)).'/photo.php')?>

    <div class="product-description col-lg-6 col-md-6 col-xs-12">
        <div class="product-title"><?=$arResult["NAME"]?></div>
        <div class="avail"></div>
        <span class="avail-text">в наличии</span>
        <div class="price-block">
            <span class="card-price"><?=str_replace(" руб.","",$arResult["ITEM_PRICES"][$arResult["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?></span><span class="rubl">i</span>
            <a href="" class="price-block-cart" cz-data="addtocart" cz-data-buy="<?=$arResult["ID"]?>">в корзину</a>
        </div>
        <?require(realpath(dirname(__FILE__)).'/properties.php')?>
    </div>
</div></div>