<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<?if($arResult["NavPageCount"] > 1):?>
<div class="page-nav text-center"><nav aria-label="Page navigation"><ul class="pagination">
    <?//Назад
    if($arResult["NavPageNomer"] == 1):?>
        <li class="disabled"><span aria-hidden="true"><i class="icon icon-pagination_prev"></i></span></li>
    <?else:?><li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" aria-label="Previous">
            <span aria-hidden="true"><i class="icon icon-pagination_prev"></i></span></a></li>
    <?endif?>
    <?//Цифры
    while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

        <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <?/*<b><?=$arResult["nStartPage"]?></b>*/?>
            <li class="active"><a href="#"><?=$arResult["nStartPage"]?> <span class="sr-only">(current)</span></a></li>
        <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
            <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
        <?else:?>
            <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
        <?endif?>
        <?$arResult["nStartPage"]++?>
    <?endwhile?>
    <?//Веперд
    if($arResult["NavPageNomer"] == $arResult["NavPageCount"]):?>
        <li class="disabled"><span aria-hidden="true"><i class="icon icon-pagination_next"></i></span></li>
    <?else:?><li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" aria-label="Next">
        <span aria-hidden="true"><i class="icon icon-pagination_next"></i></span></a></li>
    <?endif?>
</ul></nav></div>
<?endif?>
