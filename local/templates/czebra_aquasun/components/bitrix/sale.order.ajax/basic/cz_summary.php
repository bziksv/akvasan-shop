<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = true; //($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
//print_r($arResult["GRID"]);
?>
<?//print_r($arResult);?>
<table class="table-cart">
    <thead>
        <tr>
            <th>Товар</th>
            <th>Артикул</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?foreach ($arResult["BASKET_ITEMS"] as $k => $arItem):?> 
        <tr>
            <td class="ct-product">
                <a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
                    <img src="<?=$arItem["PREVIEW_PICTURE_SRC"] ? $arItem["PREVIEW_PICTURE_SRC"] : SITE_TEMPLATE_PATH."/front/build/img/no_photo.png";?>" alt="<?=$arItem["NAME"];?>">
                    <span><?=$arItem["NAME"];?></span>
                </a>
            </td>
            <td class="ct-article"><?=$arItem["PROPERTY_CML2_ARTICLE_VALUE"];?></td>
            
            <?$arItem["BASE_PRICE_FORMATED"] = str_replace("₸", "<sup>╤</sup>", $arItem["BASE_PRICE_FORMATED"]);?>
            <td class="ct-price"><?=$arItem["BASE_PRICE_FORMATED"];?></td>
            <td class="ct-quantity">
                <div class="input-append spinner" data-trigger="spinner">
                    <a href="javascript:void(0);" class="spin-down" data-spin="down">-</a>
                    <input class="itext" type="text" value="<?=$arItem["QUANTITY"];?>" data-rule="quantity">
                    <a href="javascript:void(0);" class="spin-up" data-spin="up">+</a>
                </div>
				<input type="hidden" id="basket-id-<?=$arItem["ID"]?>" value="<?=$arItem["ID"]?>">
                <input type="hidden" id="price-<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"];?>">
            </td>
            <?$arItem["SUM"] = str_replace("₸", "<sup>╤</sup>", $arItem["SUM"]);?>
            <td class="ct-sum" id="sum-<?=$arItem["ID"]?>"><?=$arItem["SUM"];?></td>
            <td class="ct-delete"><a href="/personal/order/make/?basketAction=delete&id=<?=$arItem["ID"]?>" class="ct-del">
				<input type="hidden" id="basket-id-<?=$arItem["ID"]?>" value="<?=$arItem["ID"]?>" />
			</a></td>
            
        </tr>
    <?endforeach;?>
    </tbody>
</table>
<?$arResult["ORDER_PRICE_FORMATED"] = str_replace("₸", "<sup>╤</sup>", $arResult["ORDER_PRICE_FORMATED"]);?>
<div class="cart-total">
	Сумма заказа:<span> <?=$arResult["ORDER_PRICE_FORMATED"]?></span>
</div>
