<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)  die();
$this->setFrameMode(true);?>
<div class="slider-partners col-lg-12 col-md-12 col-xs-12"><ul class="bxslider">
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
<li><img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"></li>
<?endforeach?>
</ul></div>

<div class="slider-partners-mobile col-xs-12"><ul class="bxslider"><li>
    <?foreach($arResult["ITEMS"] as $key => $arItem):?>
        <?if($key%3 == 0 && $key > 0):?>
            </li><li>
        <?endif?>
        <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>">
    <?endforeach?>
</li></ul></div>