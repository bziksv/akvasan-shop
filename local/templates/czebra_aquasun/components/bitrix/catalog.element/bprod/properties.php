<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="block-info"><div class="row"><div class="column1 col-lg-4 col-md-4">
<?
$i = 0;
foreach ($arResult["FULL_PROP"] as $prop):
    $i++;
    if ($i <= 12) : ?>
        <div class="info-product"><div class="row">
            <div class="title-info-product col-lg-12 col-md-12 col-xs-6"><?=$prop["NAME"]?></div>
            <div class="description-info-product col-lg-12 col-md-12 col-xs-6"><?=$prop["VALUE"]?></div>
        </div></div>
        <?if($i%4 == 0 && $i != 12) :?>
            </div><div class="column2 col-lg-4 col-md-4">
        <?endif?>
    <?endif?>
<?endforeach;?>
</div></div></div>
<?if(count($arResult["FULL_PROP"]) > 12) :?>
<div id="props_all" class="block-info" style="display: none;"><div class="row"><div class="column1 col-lg-4 col-md-4">
    <?
    $i = 0;
    foreach ($arResult["FULL_PROP"] as $prop):
        $i++;
        if ($i > 12) : ?>
            <div class="info-product"><div class="row">
                    <div class="title-info-product col-lg-12 col-md-12 col-xs-6"><?=$prop["NAME"]?></div>
                    <div class="description-info-product col-lg-12 col-md-12 col-xs-6"><?=$prop["VALUE"]?></div>
            </div></div>
            <?$mod = intval((count($arResult["FULL_PROP"])-12)/3);?>
            <?if($mod> 0 && $i%$mod == 0 && $i != count($arResult["FULL_PROP"])) :?>
                </div><div class="column2 col-lg-4 col-md-4">
            <?endif?>
        <?endif?>
    <?endforeach;?>
</div></div></div>
<hr>
<a href="" id="all-info" class="all-info">все характеристики</a>
<button id="all-info2" class="all-info-arrow"></button>
<?endif?>