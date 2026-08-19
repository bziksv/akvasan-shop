<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<input type="hidden" id="ajaxCountPages" value="<?=$arResult["NavPageCount"]?>" />
<input type="hidden" id="ajaxNumberPage" value="<?=$arResult["NavPageNomer"]?>" />
<?if($arResult["NavPageCount"] > 1):?>
<div class="pagination-workarea col-lg-12 col-md-12 col-xs-12"><div class="paginations">
    <?//Назад
    if($arResult["NavPageNomer"] == 1):?>

    <?else:?>
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="prev-page"><img src="<?=SITE_TEMPLATE_PATH?>/front/images/pre.png"></a>
    <?endif?>
<ul>
<?//Цифры
while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
    <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
        <li><a href="javascript:void(0);" cz-data-url="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="active-pagination"><?=$arResult["nStartPage"]?></a></li>
    <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
    <?else:?>
        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
    <?endif?>
    <?$arResult["nStartPage"]++?>
<?endwhile?>
</ul>
    <?//Веперд
    if($arResult["NavPageNomer"] == $arResult["NavPageCount"]):?>

    <?else:?>
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="post-page"><img src="<?=SITE_TEMPLATE_PATH?>/front/images/post.png"></a>
    <?endif?>
</div></div>
<?endif?>