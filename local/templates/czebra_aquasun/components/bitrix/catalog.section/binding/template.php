<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<div class="row">
<?foreach ($arResult['ITEMS'] as $arItem):
    $sum += $arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRICE"];
    ?>
<div class="new-item col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="new-product-wrapper"><div class="new-product1">
    <?if (is_array($arItem["PREVIEW_PICTURE"])) :?>
        <div class="img-product"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" class="img-responsive" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></div>
    <?else:?>
        <div class="img-product"><img src="/upload/template/no_photo_200.png" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" class="img-responsive"></div>
    <?endif?>
    <span><?=$arItem['NAME']?></span>
</div></div></div>
<?endforeach?>
    <input type="hidden" id="price_complect" value="<?=$sum?>" />
</div>