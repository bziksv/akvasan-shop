<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<input type="hidden" id="ajaxCountPages" value="<?=$arResult["NavPageCount"]?>" />
<input type="hidden" id="ajaxNumberPage" value="<?=$arResult["NavPageNomer"]?>" />
<?if($arResult["NavPageCount"] > 1):?>
<div class="container-pagination">
    <?//Назад
    if($arResult["NavPageNomer"] == 1):?>

    <?else:?>
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="prev-page"><<</a>
    <?endif?>
<ul>
<?//Цифры
while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
    <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
        <li><a href="javascript:void(0);" data-cz-url="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="selected"><?=$arResult["nStartPage"]?></a></li>
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
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="post-page">>></a>
    <?endif?>

    <div class="right-block">
                                    
        <span class="display-products" id='container-sorting-bottom'>
            <div class="eye"></div>
            <?for($i=14;$i<=112;$i*=2):?>
                <a href="<?=$APPLICATION->GetCurPageParam("COUNT=$i", array("COUNT","clear_cache"));?>" <?if($i == $_REQUEST["COUNT"] || (strlen($_REQUEST["COUNT"]) == 0 && $i==14)):?>class="selected"<?endif;?> ><?=$i?></a>
            <?endfor;?>
        </span>
        
        <a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison"></span>)</a>
        
    </div>

</div>
<?endif?>