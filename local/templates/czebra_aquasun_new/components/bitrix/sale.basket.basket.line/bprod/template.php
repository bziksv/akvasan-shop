<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if($arParams["CZ_AJAX"] == "Y"):?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
        <a href="<?=$arParams["PATH_TO_BASKET"]?>"><div class="cart-header hidden-sm hidden-xs"><span class="counter-cart"><?=$arResult['NUM_PRODUCTS']?></span></div><div class="cart-mobil hidden-lg hidden-md"><span class="counter-cart-mobil"><?=$arResult['NUM_PRODUCTS']?></span></div></a>
    <?else:?>
        <a href="<?=$arParams["PATH_TO_BASKET"]?>"><div class="cart-header hidden-sm hidden-xs"><span class="counter-cart"><?=$arResult['NUM_PRODUCTS']?></span></div><div class="cart-mobil hidden-lg hidden-md"><span class="counter-cart-mobil"><?=$arResult['NUM_PRODUCTS']?></span></div></a>
    <?endif?>
<?else:?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
    <div class="place-basket cart col-lg-3 col-md-3" id="basket-in-header"><a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison">0</span>)</a><a href="<?=$arParams["PATH_TO_BASKET"]?>"><div class="cart-header hidden-sm hidden-xs"><span class="counter-cart"><?=$arResult['NUM_PRODUCTS']?></span></div><div class="cart-mobil hidden-lg hidden-md"><span class="counter-cart-mobil"><?=$arResult['NUM_PRODUCTS']?></span></div></a></div>
    <?else:?>
    <div class="place-basket cart col-lg-3 col-md-3" id="basket-in-header"><a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison">0</span>)</a><a href="<?=$arParams["PATH_TO_BASKET"]?>"><div class="cart-header hidden-sm hidden-xs"><span class="counter-cart"><?=$arResult['NUM_PRODUCTS']?></span></div><div class="cart-mobil hidden-lg hidden-md"><span class="counter-cart-mobil"><?=$arResult['NUM_PRODUCTS']?></span></div></a></div>
    <?endif?>
<?endif?>
