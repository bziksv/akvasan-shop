<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<?foreach ($arResult['ITEMS'] as $arItem):?>
<div class="additions-product" id='doptovar'><div class="row">
    <div class="checkbox-area col-lg-1 col-md-1 col-xs-1"><div class="container-checkbox">
        <input type="checkbox" id="check<?=$arItem['ID']?>" value="<?=$arItem['ID']?>">
        <label for="check<?=$arItem['ID']?>"></label>
    </div></div>
    <div class="img-additions-product col-lg-2 col-md-2 col-xs-2">
		<? if($arItem['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE']): ?>
			<div class="sticker" style="top:25px;">
				<div class="sale">-<?=$arItem['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE']?>%</div>
			</div>
		<? endif; ?>
        <?if (is_array($arItem["PREVIEW_PICTURE"])) :?>
        <div class="container-img"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></div>
        <?else:?>
        <div class="container-img"><img src="/upload/template/no_photo_200.png" alt="<?=$arItem['NAME']?>"></div>
        <?endif?>
    </div>
    <div class="info-additions-product col-lg-6 col-md-6 col-xs-6">
        <div class="title-info"><?=$arItem['NAME']?></div>
        <div class="description">
            <span>Арт. <span><?=$arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></span>
            <?/*<span>Д/Ш <span>70x30 см</span></span>
            <span>Цвет: серый</span>*/?>
        </div>
    </div>
    <div class="price-additions-product col-lg-2 col-md-2 col-xs-2">
        <span><?=str_replace(" руб.","",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"])?> р.</span>
    </div>
</div></div>
<?endforeach?>