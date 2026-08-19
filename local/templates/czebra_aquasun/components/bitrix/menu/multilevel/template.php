<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if (!empty($arResult)) :?>
<svg style="display: none;">
    <symbol id="arrow_up" viewBox="0 0 129 129">
        <path id="Layer" class="shp0" d="M7.7,94.4c1.6,1.6 4.2,1.6 5.8,0l51,-51.1l51.1,51.1c1.6,1.6 4.2,1.6 5.8,0c1.6,-1.6 1.6,-4.2 0,-5.8l-53.9,-53.9c-0.8,-0.8 -1.8,-1.2 -2.9,-1.2c-1,0 -2.1,0.4 -2.9,1.2l-53.9,53.9c-1.7,1.6 -1.7,4.2 -0.1,5.8z" />
    </symbol>
    <symbol id="arrow_down" viewBox="0 0 129 129">
        <path id="Layer" class="shp0" d="M121.3,34.6c-1.6,-1.6 -4.2,-1.6 -5.8,0l-51,51.1l-51.1,-51.1c-1.6,-1.6 -4.2,-1.6 -5.8,0c-1.6,1.6 -1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2c1,0 2.1,-0.4 2.9,-1.2l53.9,-53.9c1.7,-1.6 1.7,-4.2 0.1,-5.8z" />
    </symbol>
</svg>
<ul>
<?
$previousLevel = 0;
foreach($arResult as $arItem) :?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel) :?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>
	<?if ($arItem["IS_PARENT"]) :?>
		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
    <li<?if ($arItem["SELECTED"]):?> class="active"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?>
        <?if ($arItem["SELECTED"]):?>
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xlink:href="#arrow_up" /></svg>
        <?else:?>
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xlink:href="#arrow_down" /></svg>
        <?endif?>
    </a>
				<ul class="drop-menu collapse">
		<?else :?>
			<li<?if ($arItem["SELECTED"]):?> class="active"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?>

        <?if ($arItem["SELECTED"]):?>
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xlink:href="#arrow_up" /></svg>
        <?else:?>
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xlink:href="#arrow_down" /></svg>
        <?endif?>
    </a>
				<ul class="drop-menu collapse">
		<?endif?>
	<?else :?>
		<?if ($arItem["PERMISSION"] > "D") :?>
			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li<?if ($arItem["SELECTED"]):?> class="active"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?else :?>
				<li<?if ($arItem["SELECTED"]):?> class="active"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>
		<?else :?>
			<?if ($arItem["DEPTH_LEVEL"] == 1) :?>
				<li<?if ($arItem["SELECTED"]):?> class="active"<?endif?>><a href=""><?=$arItem["TEXT"]?></a></li>
			<?else :?>
				<li><a href=""><?=$arItem["TEXT"]?></a></li>
			<?endif?>
		<?endif?>
	<?endif?>
	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
<?endforeach?>
<?if ($previousLevel > 1) :?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>
</ul>
<?endif?>