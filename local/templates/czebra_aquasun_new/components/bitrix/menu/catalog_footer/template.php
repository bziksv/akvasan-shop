<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if (!empty($arResult)) :?>
<!-- <svg style="display: none;">
    <symbol id="arrow_up" viewBox="0 0 129 129">
        <path id="Layer" class="shp0" d="M7.7,94.4c1.6,1.6 4.2,1.6 5.8,0l51,-51.1l51.1,51.1c1.6,1.6 4.2,1.6 5.8,0c1.6,-1.6 1.6,-4.2 0,-5.8l-53.9,-53.9c-0.8,-0.8 -1.8,-1.2 -2.9,-1.2c-1,0 -2.1,0.4 -2.9,1.2l-53.9,53.9c-1.7,1.6 -1.7,4.2 -0.1,5.8z" />
    </symbol>
    <symbol id="arrow_down" viewBox="0 0 129 129">
        <path id="Layer" class="shp0" d="M121.3,34.6c-1.6,-1.6 -4.2,-1.6 -5.8,0l-51,51.1l-51.1,-51.1c-1.6,-1.6 -4.2,-1.6 -5.8,0c-1.6,1.6 -1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2c1,0 2.1,-0.4 2.9,-1.2l53.9,-53.9c1.7,-1.6 1.7,-4.2 0.1,-5.8z" />
    </symbol>
</svg> -->
<div class="menu-footer-left col-lg-6 col-md-6">
<ul>
<?foreach($arResult as $key=>$arItem) :?>
	<?if($key == round(count($arResult) / 2)):?>
		</ul></div><div class="menu-footer-right col-lg-6 col-md-6"><ul>
	<?endif?>
		<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
<?endforeach?>
</ul>
<?endif?>
