<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$frame = $this->createFrame()->begin('<div class="cart-user col-lg-2 col-md-2"><div class="cart"></div><a href="/personal/cart/">Корзина</a><a href="/personal/cart/" class="cart-product">()</a></div>');
?>
<?if($arParams["CZ_AJAX"] == "Y"):?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
        <div class="cart"></div><a href="<?=$arParams["PATH_TO_BASKET"]?>">Корзина</a><a href="<?=$arParams["PATH_TO_BASKET"]?>" class="cart-product">(<?=$arResult['NUM_PRODUCTS']?>)</a>
    <?else:?>
        <div class="cart"></div><a href="<?=$arParams["PATH_TO_BASKET"]?>">Корзина</a><a href="<?=$arParams["PATH_TO_BASKET"]?>" class="cart-product">(пусто)</a>
    <?endif?>
<?else:?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
    <div class="place-basket cart-user col-lg-2 col-md-2"><div class="cart"></div><a href="<?=$arParams["PATH_TO_BASKET"]?>">Корзина</a><a href="<?=$arParams["PATH_TO_BASKET"]?>" class="cart-product">(<?=$arResult['NUM_PRODUCTS']?>)</a></div>
    <?else:?>
    <div class="place-basket cart-user col-lg-2 col-md-2"><div class="cart"></div><a href="<?=$arParams["PATH_TO_BASKET"]?>">Корзина</a><a href="<?=$arParams["PATH_TO_BASKET"]?>" class="cart-product">(пусто)</a></div>
    <?endif?>
<?endif?>
