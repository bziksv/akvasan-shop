<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?/*
<div class="div workarea-cart"><div class="container">
<?if(count($arResult['GRID']['ROWS']) > 0):?>
    <div class="head-table-cart"><div class="row">
        <div class="column1 col-lg-2 col-md-2">Изображение</div>
        <div class="column2 col-lg-2 col-md-2">Название</div>
        <div class="column3 col-lg-2 col-md-2">Цена</div>
        <div class="column4 col-lg-2 col-md-2">Количество</div>
        <div class="column5 col-lg-2 col-md-2">Сумма</div>
        <div class="column6 col-lg-2 col-md-2">Удалить</div>
    </div></div>
<?
foreach($arResult['GRID']['ROWS'] as $item):
?>
<div class="body-table-cart"><div class="row">
    <div class="column1 col-lg-2 col-md-2 col-xs-6"><div class="img-cart">
        <a hhref="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank"><img src="<?=$item["DETAIL_PICTURE_SRC"]?>" alt="<?=$item["NAME"]?>"></a>
    </div></div>

    <div class="column-block col-lg-2 col-md-2 col-xs-6"><div class="row">
        <div class="column-block-left col-lg-6 col-xs-12"><a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank"><?=$item["NAME"]?></a></div>
        <div class="column-block-right col-lg-6 col-xs-12" data-price="<?=$item["ID"]?>"><?=$item["PRICE_FORMATED_RUB"]?></div>
    </div></div>
            <div class="column4 col-lg-2 col-md-2 col-xs-6">
                <div class="sum">
                    <a href=""><div class="minus"></div></a>
                    <input type="text" data-id-count="<?=$item["ID"]?>" name="quantity" value="<?=$item["QUANTITY"]?>">
                    <a href=""><div class="plus"></div></a>
                </div>
            </div>
    <div class="column5 col-lg-2 col-md-2 col-xs-6" data-sum="<?=$item["ID"]?>"><?=$item["SUM_FULL_PRICE_FORMATED_RUB"]?></div>
    <div class="column6 col-lg-2 col-md-2">
        <a href="" name="del-basket-item" data-del-id="<?=$item["ID"]?>"><div class="deleted"></div></a>
    </div>
</div></div>
<?endforeach;?>
        <div class="bottom-form"><div class="row"><div class="label-form col-lg-4 col-md-4"></div><div class="totals-form col-lg-8 col-md-8">
        <span class="total">Итого</span>
        <span class="total-price"><?=$arResult["allSum_FORMATED"]?></span>
        <a href="<?=$arParams["PATH_TO_ORDER"]?>">Оформить заказ</a>
    </div></div></div>
<?else:print_r($arResult);?>
    <p>Ваша корзина пуста. Начните делать <a href="/catalog/">покупки прямо сейчас</a>.</p>
<?endif?>
</div></div>
*/?>

<div class="div workarea-cart"><div class="container">
<?if(count($arResult['GRID']['ROWS']) > 0):?>
    <div class="head-table-cart"><div class="row">
        <div class="column1 col-lg-2 col-md-2">Изображение</div>
        <div class="column2 col-lg-2 col-md-2">Название</div>
        <div class="column3 col-lg-2 col-md-2">Цена</div>
        <div class="column4 col-lg-2 col-md-2">Количество</div>
        <div class="column5 col-lg-2 col-md-2">Сумма</div>
        <div class="column6 col-lg-2 col-md-2">Удалить</div>
    </div></div>
<?
foreach($arResult['GRID']['ROWS'] as $item):
?>
<div class="body-table-cart"><div class="row">

    <div class="column-block col-lg-2 col-lg-push-2 col-md-2 col-md-push-2 col-xs-12"><div class="row">
        <div class="column-block-left col-lg-6 col-xs-12"><a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank"><?=$item["NAME"]?></a></div>
        <div class="column-block-right col-lg-6 col-xs-12" data-price="<?=$item["ID"]?>"><?=$item["PRICE_FORMATED_RUB"]?></div>
    </div></div>

    <div class="column1 col-lg-2 col-lg-pull-2 col-md-2 col-md-pull-2 col-xs-6">
		<div class="img-cart">
			 <? if($item['DISCOUNT_PRICE_PERCENT']): ?>
				<div class="sticker" style="top:0px;left:0px;">
					<div class="sale">-<?=$item['DISCOUNT_PRICE_PERCENT_FORMATED']?></div>
				</div>
			<? endif; ?>
			<a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank"><img src="<?=$item["DETAIL_PICTURE_SRC"]?>" alt="<?=$item["NAME"]?>"></a>
		</div>
	</div>

    

    <div class="column4 col-lg-2 col-md-2 col-xs-6">
        <div class="sum">
            <a href="" class="quan-minus"><div class="minus"></div></a>
            <input type="text" data-id-count="<?=$item["ID"]?>" name="quantity" value="<?=$item["QUANTITY"]?>">
            <a href="" class="quan-plus"><div class="plus"><div class="sprite-plus-noactive"></div></div></a>
        </div>
    </div>

    <div class="column6 col-lg-2 col-lg-push-2 col-md-2 col-md-push-2 col-xs-6">
        <a href="" name="del-basket-item" data-del-id="<?=$item["ID"]?>"><div class="deleted"></div></a>
    </div>

    <div class="column5 col-lg-2 col-lg-pull-2 col-md-2 col-lg-pull-2 col-xs-6">
	<? if($item['DISCOUNT_PRICE_PERCENT']): ?>
		<div class="old_price"><?=str_replace("руб.","р.",$item["FULL_PRICE_FORMATED"])?></div>
	<? endif; ?>
		<div class="price" data-sum="<?=$item["ID"]?>"><?=str_replace("i","р.",$item["SUM_FULL_PRICE_FORMATED_RUB"])?></div>
	</div>
    

</div></div>
<?endforeach;?>
        <div class="bottom-form"><div class="row"><div class="label-form col-lg-4 col-md-4"><a href="/catalog/">Продолжить покупки</a></div><div class="totals-form col-lg-8 col-md-8">
        <span class="total">Итого к оплате:</span>
        <span class="total-price"><?=str_replace("i","руб.",$arResult["allSum_FORMATED"])?></span>
        <a href="<?=$arParams["PATH_TO_ORDER"]?>">Оформить заказ</a>
    </div></div><a href="/catalog/" class="bottom-link-cart hidden-lg hidden-md">Продолжить покупки</a></div>
<?else:?>
    <p>Ваша корзина пуста. Начните делать <a href="/catalog/">покупки прямо сейчас</a>.</p>
<?endif?>
</div></div>





