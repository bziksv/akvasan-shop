<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if (!empty($arResult)) :

if (!$arResult['PROPERTY']['DELETE_INDEX']) {
    $arResult['PROPERTY']['DELETE_INDEX'] = [];
}
?>
<nav class="slide-menu" id="test-menu-right">

    <div class="controls">
        <button type="button" class="btn slide-menu__control" data-action="back">Назад</button>
        <button type="button" class="btn slide-menu__control" data-action="close">Закрыть</button>
    </div>

    <ul>
        <?
        $previousLevel = 0;
        foreach($arResult as $arItem) :
        ?>

        <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel) :?>
            <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
        <?endif?>

        <?if ($arItem["IS_PARENT"]) :?>
        <?if ($arItem["DEPTH_LEVEL"] == 1):

            $ch = explode('/',$arItem["LINK"]);
            $class = $ch[2];
        ?>
        <li<?if ($arItem["SELECTED"]):?> class="selected"<?endif?>>

            <? if(in_array($arItem['PARAMS']['ID'], $arResult['PROPERTY']['DELETE_INDEX'])): ?>
                <a href="<?=$arItem["LINK"]?>"  class='<?=$class?>' text-js="<?=$arItem["TEXT"]?>"></a>
            <? else: ?>
                <a href="<?=$arItem["LINK"]?>"  class='<?=$class?>'>
                    <? if($arItem["PARAMS"]["PICTURE"]): ?>
                        <img src="<?=$arItem["PARAMS"]["PICTURE"]?>" data-id="<?=$arItem["PARAMS"]["ID"]?>" style="width: 30px;margin-right: 10px;">
                    <? endif; ?>
                    <?=$arItem["TEXT"]?>
                </a>
            <? endif; ?>

            <ul class="menu-item">
                <?else :?>
                <li<?if ($arItem["SELECTED"]):?> class="selected"<?endif?>>

                    <? if(in_array($arItem['PARAMS']['ID'], $arResult['PROPERTY']['DELETE_INDEX'])): ?>
                        <a href="<?=$arItem["LINK"]?>"  class='<?=$class?>' text-js="<?=$arItem["TEXT"]?>"></a>
                    <? else: ?>
                        <a href="<?=$arItem["LINK"]?>"  class='<?=$class?>'><?=$arItem["TEXT"]?></a>
                    <? endif; ?>

            <ul class="menu-item">
                <?endif?>
                <?else :?>
                    <?if ($arItem["PERMISSION"] > "D") :?>
                        <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                            <?if ($arItem["DEPTH_LEVEL"] == 1){
                                $ch = explode('/',$arItem["LINK"]);
                                $class = $ch[2];
                            }
                            ?>
                            <li<?if ($arItem["SELECTED"]):?> class="selected"<?endif?>>
                                <? if(in_array($arItem['PARAMS']['ID'], $arResult['PROPERTY']['DELETE_INDEX'])): ?>
                                    <a href="<?=$arItem["LINK"]?>" class='<?=$class?>' text-js='<?=$arItem["TEXT"]?>'></a>
                                <? else: ?>
                                    <a href="<?=$arItem["LINK"]?>" class='<?=$class?>'><?=$arItem["TEXT"]?></a>
                                <? endif; ?>
                            </li>
                        <?else :?>
                            <li<?if ($arItem["SELECTED"]):?> class="selected"<?endif?>>
                                <? if(in_array($arItem['PARAMS']['ID'], $arResult['PROPERTY']['DELETE_INDEX'])): ?>
                                    <a href="<?=$arItem["LINK"]?>" text-js='<?=$arItem["TEXT"]?>'></a>
                                <? else: ?>
                                    <a href="<?=$arItem["LINK"]?>">
                                        <? if($arItem["PARAMS"]["PICTURE"]): ?>
                                            <img src="<?=$arItem["PARAMS"]["PICTURE"]?>" data-id="<?=$arItem["PARAMS"]["ID"]?>" style="width: 30px;margin-right: 10px;">
                                        <? endif; ?>
                                        <?=$arItem["TEXT"]?>
                                    </a>
                                <? endif; ?>
                            </li>
                        <?endif?>
                    <?else :?>
                        <?if ($arItem["DEPTH_LEVEL"] == 1) :?>
                            <li<?if ($arItem["SELECTED"]):?> class="selected"<?endif?>>
                                <? if(in_array($arItem['PARAMS']['ID'], $arResult['PROPERTY']['DELETE_INDEX'])): ?>
                                    <a href="" text-js="<?=$arItem["TEXT"]?>"></a>
                                <? else: ?>
                                    <a href=""><?=$arItem["TEXT"]?></a>
                                <? endif; ?>
                            </li>
                        <?endif?>
                    <?endif?>
                <?endif?>
                <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
        <?endforeach?>

        <?if ($previousLevel > 1) :?>
            <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
        <?endif?>
    </ul>
</nav>
<?endif?>
