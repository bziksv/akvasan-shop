<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$config = \Bitrix\Main\Config\Configuration::getInstance();
$configConnections = $config->get("user_params");

$props_inc = 1;
foreach ($arResult["ITEMS"] as $arItem) :
?>
<div class="container-product col-lg-4 col-md-4 <?=$arItem["CLASS"]?>" id="<?=$arItem['ID']?>">
	 
	 <? if($arItem['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE'] || $arItem['PROPERTIES']['SKIDKA_PRI_OPLATE_V_MAGAZINE']['VALUE']): ?>
		<div class="sticker" <? if($arItem['PROPERTIES']['NOVINKA']['VALUE'] || $arItem['PROPERTIES']['AKTSIYA']['VALUE']): ?> style="top: 35px;left: 10px;" <? endif; ?>>
			<div class="sale">-<?=($arItem['PROPERTIES']['SKIDKA_NA_TOVAR_V_KORZINE']['VALUE']) ?: $arItem['PROPERTIES']['SKIDKA_PRI_OPLATE_V_MAGAZINE']['VALUE']?>%</div>
		</div>
	<? endif; ?>
	
    <div class="img-product">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link-product"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></a>
    </div>
    
    <div class="name-product">
    	<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link-product">
			<? if($arItem["PROPERTIES"]["SITE_NAME"]["VALUE"]):?>
				<p><?=$arItem["PROPERTIES"]["SITE_NAME"]["VALUE"]?></p>
			<? else:?>
				<p><?=TruncateText($arItem['NAME'], 80)?></p>
			<? endif; ?>
		</a>
        <?if($arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] !=''):?>
            <span class="vendor-code">Арт.<?=$arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
        <?endif;?>
            <span class="manufacturer-country">
            <img src="/upload/template_new/flag_<?=Cutil::translit($arItem["PROPERTIES"]["STRANA"]["VALUE"],"ru",array())?>.png" alt="<?=$arItem["PROPERTIES"]["STRANA"]["VALUE"]?>">
            <a href="/country/<?=Cutil::translit($arItem["PROPERTIES"]["STRANA"]["VALUE"],"ru",array())?>/" text-js="<?=$arItem["PROPERTIES"]["STRANA"]["VALUE"]?>"></a>
        </span>
    </div>
    <?if($arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_PRICE"] != ""):?>
    <div class="price-product-catalog">
        <span class="price"><?=str_replace(" руб.","р.",$arItem["ITEM_PRICES"][$arItem["ITEM_PRICE_SELECTED"]]["PRINT_BASE_PRICE"])?></span>
		
		<? if($configConnections['cart']): ?>
			<a href="" class="add-to-cart" data-cz-buy="<?=$arItem["ID"]?>" data-cz="addtocart" text-js="В корзину"></a>
		<? else: ?>
			<a href="" class="add-to-cart" data-cz-buy="<?=$arItem["ID"]?>" data-cz="addtocart">В корзину</a>
		<? endif; ?>
    </div>
    <?endif;?>
	<?if($arItem["NOTE"]):?>
		<div class="price-product-catalog blur_in">
			<span class="price"><?=$arItem["NOTE"]?></span>
		 </div>
	<?endif;?>
    <div class="hidden-text">
		<? if($arResult['UF_PROPS_JS'] >= $props_inc):?>
        <?
            $i = 0;
            foreach ($arItem["PROPERTIES"] as $pid => $arProperty) : ?>
                <?if(is_string($arProperty["VALUE"]) && strlen($arProperty["VALUE"]) > 0 && !in_array($pid, \Czebra\Base\Consts::STOP_PROP)):?>
                <div class="hidden-info" text-js='<span class="info-left"><?=$arProperty["NAME"]?>:</span><span class="info-right"><?=$arProperty["VALUE"];?></span>'></div>
                    <?
                    $i++;

                    if ($i == 4) {
                    break;
                    }
                    ?>
                <?endif?>
            <?endforeach;?>
		<? else: ?>
		
		<?
            $i = 0;
            foreach ($arItem["PROPERTIES"] as $pid => $arProperty) :?>
                <?if(is_string($arProperty["VALUE"]) && strlen($arProperty["VALUE"]) > 0 && !in_array($pid, \Czebra\Base\Consts::STOP_PROP)):?>
                <div class="hidden-info">
					<span class="info-left"><?=$arProperty["NAME"]?>:</span>
					<span class="info-right"><?=$arProperty["VALUE"];?></span>
				</div>
                    <?
                    $i++;

                    if ($i == 4) {
						break;
                    }
                    ?>
                <?endif?>
            <?endforeach;?>

		<? endif; ?>

		<? if($arItem["PROPERTIES"]["PREVIEW_TEXT_SEO"]["VALUE"]): ?>
		<div class="preview-text-seo">
			<a href="#">▼</a>
			<p><?=$arItem["PROPERTIES"]["PREVIEW_TEXT_SEO"]["~VALUE"]["TEXT"]?></p>
		</div>
		<? endif; ?>
    </div>
    <a href="" data-compare-action="add" data-compare-id="<?=$arItem["ID"]?>" class="arrow-slide"></a>
    <div class="special-offers">
        <?if($arItem['PROPERTIES']['NOVINKA']['VALUE'] != ''):?>
            <span>Новинка</span> <img src="/local/templates/czebra_aquasun_new/front/img/novelty.png" alt="Новинка">
        <?elseif($arItem['PROPERTIES']['AKTSIYA']['VALUE'] !=''):?>
            <span>Акция</span> <img src="/local/templates/czebra_aquasun_new/front/img/stock.png" alt="Акция">
        <?endif;?>
    </div>
</div>
<?
$props_inc++;
endforeach?>
<?if ($arParams['AJAX'] == 'Y') :?>
<div id="wrap-pager-ajax" style="display: none;"><?=$arResult["NAV_STRING"];?></div>
<?endif?>