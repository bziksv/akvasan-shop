<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<?=$arResult["NAME"]?>
<?=$arResult["DISPLAY_ACTIVE_FROM"]?>
<?=$arResult["DETAIL_TEXT"];?>
<?=$arResult["PREVIEW_TEXT"];?>

<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
	<img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"/>
<?endif?>

<?foreach($arResult["FIELDS"] as $code=>$value):?>
	<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
<?endforeach;?>

<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
	<?=$arProperty["NAME"]?>:
	<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
		<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
	<?else:?>
		<?=$arProperty["DISPLAY_VALUE"];?>
	<?endif?>
<?endforeach;?>