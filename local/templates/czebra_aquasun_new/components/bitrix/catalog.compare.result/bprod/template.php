<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$isAjax = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$isAjax = (
		(isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'Y')
		|| (isset($_POST['compare_result_reload']) && $_POST['compare_result_reload'] == 'Y')
	);
}

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

?>


<div class="workarea-product-compare">
	<div class="compare-slider">
		<div class="bxslider">
			<?foreach($arResult['ITEMS'] as $arItem):?>
			<div class="slide-compare">
				<div class="workarea-slide-compare">
					<div class="img-product-compare">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"></a>
					</div>
					<div class="name-product-compare">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem['NAME']?></a> 
					</div>
					<div class="block-price-compare">
						<span><?=str_replace("руб.","р.",$arItem['MIN_PRICE']['PRINT_VALUE'])?></span>
						<a href="" data-cz-buy="<?=$arItem["ID"]?>" data-cz="addtocart">В корзину</a>
					</div>
					<div class="deleted-product-compare">
						<a href="" data-compare-id="<?=$arItem["ID"]?>">
							<span class="hidden-deleted hidden-sm hidden-xs">Удалить</span>
							<div class="deleted hidden-sm hidden-xs"></div>
							<span class="del-icon hidden-lg hidden-md"></span>
						</a>
						
					</div>
				</div>
				<div class="value-info">
					<?foreach($arItem['DISPLAY_PROPERTIES'] as $key1=>$val1):?>
						<?if(!in_array($key1,Czebra\Base\Consts::STOP_PROP_ELEMENT)):?>
							<div class="wrapp-info">
								<?if($val1['VALUE'] !=""):?>
									<span><?=$val1['VALUE']?></span>
								<?else:?>
									<span>-</span>
								<?endif;?>
							</div>	
						<?endif;?>
					<?endforeach;?>
				</div>
			</div>
			<?endforeach;?>
		</div>
		<div class="fixed-slide">
			<div class="empty-block"></div>
			<div class="key-info">
				<?foreach($arResult['SHOW_PROPERTIES'] as $key2=>$val2):
					if(!in_array($key2,Czebra\Base\Consts::STOP_PROP_ELEMENT)):?>
					<div class="wrapp-info">
						<span><?=$val2['NAME']?></span>
					</div>	
					<?endif?>
				<?endforeach;?>
			</div>
		</div>
	</div>
</div>
