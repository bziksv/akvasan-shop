<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="container-characteristics">
    <div class="characteristic">
        <div class="name-characteristic"><b>КОД ТОВАРА</b></div>
        <div class="value-characteristic"><?=$arResult["ID"];?></div>
    </div>
<?
$i = 0;
foreach ($arResult["FULL_PROP"] as $prop):
    $i++;
    ?>
    <?if($i == 13) :?>
    <div id="props_all" style="display: none;">
    <?endif?>
    <?if($prop['NAME']=='Бренд'):?>
        <div class="characteristic">
            <div class="name-characteristic"><?=$prop["NAME"]?></div>
            <div class="value-characteristic"><a href="/brands/<?=Cutil::translit($prop["VALUE"],"ru",array())?>/"><?=$prop["VALUE"]?></a></div>
            <?if ($prop["IMG"]):?> 
                <a href="/brands/<?=Cutil::translit($prop["VALUE"],"ru",array())?>/" class="brand-img hidden"><img src="<?=$prop["IMG"];?>" alt="<?=$prop["NAME"]?>"></a>
            <?endif?>
        </div>
    <?elseif($prop['NAME']=='Серия') :?>    
        <div class="characteristic">
            <div class="name-characteristic"><?=$prop["NAME"]?></div>
            <div class="value-characteristic"><a href="/seria/<?=Cutil::translit($prop["VALUE"],"ru",array())?>/"><?=$prop["VALUE"]?></a></div>
        </div>
    <?elseif($prop['NAME']=='Страна') :?>
        <div class="characteristic">
            <div class="name-characteristic"><?=$prop["NAME"]?></div>
            <div class="value-characteristic"><img src="/upload/template_new/flag_<?=Cutil::translit($arResult["PROPERTIES"]["STRANA"]["VALUE"],"ru",array())?>.png" alt="<?=$prop["VALUE"]?>"> <a href="/country/<?=Cutil::translit($prop["VALUE"],"ru",array())?>/"><?=$prop["VALUE"]?></a></div>
        </div>
    <?else:?>
        <div class="characteristic">
            <div class="name-characteristic"><?=$prop["NAME"]?></div>
            <div class="value-characteristic"><?=$prop["VALUE"]?></div>
        </div>
    <?endif?>
<?endforeach;?>
<?if($i > 12) :?>
    </div>
<?endif?>
<?if ($i > 12):?>
    <a href="" id="all-info" class="all-characteristic">все характеристики</a>
<?endif;?>
</div>
<!-- <div class="container-characteristics">
        
    <div class="characteristic">
        <div class="name-characteristic">Артикул</div>
        <div class="value-characteristic">235860000</div>
    </div>
        <div class="characteristic">
        <div class="name-characteristic">Бренд</div>
        <div class="value-characteristic"><a href="#">Roca</a></div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Серия</div>
        <div class="value-characteristic"><a href="#">Contesa</a></div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Страна</div>
        <div class="value-characteristic"><img src="img/flages.png" alt="Испания"> Испания</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Материал</div>
        <div class="value-characteristic">сталь</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Форма</div>
        <div class="value-characteristic">прямоугольная</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Цвет</div>
        <div class="value-characteristic">белый, серый, черный</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Длина, см</div>
        <div class="value-characteristic">170</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Ширина, см</div>
        <div class="value-characteristic">70</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Глубина, см</div>
        <div class="value-characteristic">40</div>
    </div>
    <div class="characteristic">
        <div class="name-characteristic">Расположение перелива</div>
        <div class="value-characteristic">стандартное</div>
    </div>
    
    <a href="#" class="all-characteristic">Все характеристики</a>
    
</div> -->