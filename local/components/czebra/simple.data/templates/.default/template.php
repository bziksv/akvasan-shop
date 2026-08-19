<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);?>

<pre><?print_r($arResult["ITEMS"])?></pre>

<?foreach($arResult["ITEMS"] as $key => $arItem):?>
    <?=$arItem["NAME"]?>-<?=$arItem["DISPLAY_PROPERTIES"]["NAME"]["VALUE"]?>
<?endforeach?>
