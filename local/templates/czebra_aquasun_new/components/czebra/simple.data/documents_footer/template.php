<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)  die();
$this->setFrameMode(true);?>
<ul>
<?foreach($arResult['ITEMS'] as $key => $arItem):?>
    <li><a href="<?=$arItem['DISPLAY_PROPERTIES']['FILE']['VALUE']?>" download title="<?=$arItem["NAME"]?>"><?=$arItem['NAME']?></a></li>
<?endforeach;?>
</ul>