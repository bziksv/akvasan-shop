<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if (!empty($arResult)) :
	$chunks = array_chunk($arResult, (int)ceil(count($arResult) / 4));
	foreach ($chunks as $chunk) :?>
<div class="menu-footer-col">
<ul>
<?foreach ($chunk as $arItem) :?>
	<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
<?endforeach?>
</ul>
</div>
<?endforeach;
endif?>
