<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<div class="slider-images col-lg-9 col-md-9 col-xs-12"><ul class="bxslider">
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
<li>
    <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>">
    <div class="slide-text1"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT1"]["VALUE"]?></div>
    <div class="slide-text2"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT2"]["VALUE"]?><a href="<?=$arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"]?>">Заказать</a></div>
</li>
<?endforeach?>
</ul></div>