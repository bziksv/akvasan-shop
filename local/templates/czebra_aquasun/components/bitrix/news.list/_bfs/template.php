<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<?foreach($arResult["ITEMS"] as $arItem):?>
    <?=$arItem["NAME"]?>
    <?=$arItem["DETAIL_PAGE_URL"]?>
    <?=$arItem["DISPLAY_ACTIVE_FROM"]?>
    <?=$arItem["PREVIEW_TEXT"];?>
    
    <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>" />
            
    
    <?foreach ($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty) :?>
        <?=$arProperty["NAME"]?>:<?=$arProperty["DISPLAY_VALUE"];?>
    <?endforeach;?>    

    <?foreach ($arItem["FIELDS"] as $code => $value) :?>
        <?=GetMessage("IBLOCK_FIELD_".$code)?>:<?=$value;?>
    <?endforeach;?>
<?endforeach;?>
<?if ($arParams["DISPLAY_BOTTOM_PAGER"]) :?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

