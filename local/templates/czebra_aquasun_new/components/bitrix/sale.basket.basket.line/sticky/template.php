<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$frame = $this->createFrame()->begin('<div class="cart-sticky col-lg-2 col-md-2"><a href="/personal/cart/"></a></div>');
?>
<?if($arParams["CZ_AJAX"] == "Y"):?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
        <a href="<?=$arParams["PATH_TO_BASKET"]?>"><?=$arResult['NUM_PRODUCTS']." ".$arResult["PRODUCT(S)"]?></a>
    <?else:?>
        <a href="<?=$arParams["PATH_TO_BASKET"]?>"><?=$arResult['NUM_PRODUCTS']." ".$arResult["PRODUCT(S)"]?></a>
    <?endif?>
<?else:?>
    <?if($arResult['NUM_PRODUCTS'] > 0) :?>
    <div class="place-basket cart-sticky col-lg-2 col-md-2" id='basket-in-panel'><a href="<?=$arParams["PATH_TO_BASKET"]?>"><?=$arResult['NUM_PRODUCTS']." ".$arResult["PRODUCT(S)"]?></a></div>
    <?else:?>
    <div class="place-basket cart-sticky col-lg-2 col-md-2" id='basket-in-panel'><a href="<?=$arParams["PATH_TO_BASKET"]?>"><?=$arResult['NUM_PRODUCTS']." ".$arResult["PRODUCT(S)"]?></a></div>
    <?endif?>
<?endif?>