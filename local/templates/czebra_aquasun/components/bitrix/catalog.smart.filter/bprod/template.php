<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<?
$count_prop = 0;
foreach($arResult["ITEMS"] as $key=>$arItem) {
    if (empty($arItem["VALUES"]) || isset($arItem["PRICE"])) {
        $count_prop++;
    } elseif ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)){
        $count_prop++;
    }
}
?>

<?if(count($arResult["ITEMS"]) != $count_prop) :?>
<div class="filter">
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
    <?foreach($arResult["HIDDEN"] as $arItem):?>
        <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
    <?endforeach;?>

    <?
    $i = 0;
    //not prices
    foreach($arResult["ITEMS"] as $key=>$arItem) {
        if (
            empty($arItem["VALUES"])
            || isset($arItem["PRICE"])
        )
            continue;

        if (
            $arItem["DISPLAY_TYPE"] == "A"
            && (
                $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
            )
        )
            continue;

        $i++;

        $arCur = current($arItem["VALUES"]);
        switch ($arItem["DISPLAY_TYPE"]) {
            case "A"://NUMBERS_WITH_SLIDER
                if (floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) == 0) {
                    $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
                }
                if (floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) == 0) {
                    $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
                }
                /*?>
                <div class="block-filter__section">
                    <h5 class="block-filter__title"><?= $arItem["NAME"] ?>:</h5>
                    <div class="filter-range">
                        <div class="filter-range__row">
                            <input type="text" class="only-numeric numeric-min" data-value="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" />
                            <span>&mdash;</span>
                            <input type="text" class="only-numeric numeric-max" data-value="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" />
                        </div>
                        <div class="filter-range__range">
                            <div class="slider-square"></div>
                        </div>
                    </div>
                </div>

                <?*/?>
                <div class="filter-brand<?if($i > 1):?> hidden-filter" style="display: none;<?endif?>"><a href="" class="sh-all-prop" <?if($i > 1):?>data-prop="close"<?else:?>data-prop="stop"<?endif?>><span><?=$arItem["NAME"]?></span><?if($i > 1):?><svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow_down"></use></svg><?endif?></a><div class="row" <?if($i > 1):?>style="display: none"<?endif?>>
                        <div class="filter-price-area col-lg-4 col-md-4 col-xs-12">
                            <input type="text" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>">
                            <span class="razdel">-</span>
                            <input type="text" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>">
                            <div class="filter-<?=$key?> filter-ch"></div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('.filter-<?=$key?>').slider({
                                    min: <?=$arItem["VALUES"]["MIN"]["VALUE"]?>,
                                    max: <?=$arItem["VALUES"]["MAX"]["VALUE"]?>,
                                    step: 0.1,
                                    values: [<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>, <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>],
                                    range: true,
                                    slide: function (event, ui) {
                                        $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(ui.values[0]);
                                        $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val(ui.values[1]);
                                    }
                                });

                                $('#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>, #<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>').on('keydown', function(e){
                                    if(e.key.length == 1 && e.key.match(/[^0-9'".]/)){
                                        return false;
                                    };
                                });

                                $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>, #<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").change(function(){
                                    var vMin = $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val();
                                    var vMax = $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val();

                                    if(vMin < <?=$arItem["VALUES"]["MIN"]["VALUE"]?>){
                                        $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(<?=$arItem["VALUES"]["MIN"]["VALUE"]?>);
                                        vMin = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
                                    } else if(vMin > vMax) {
                                        $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(vMax);
                                        vMin = vMax;
                                    }

                                    if(vMax > <?=$arItem["VALUES"]["MAX"]["VALUE"]?>){
                                        $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val(<?=$arItem["VALUES"]["MAX"]["VALUE"]?>);
                                        vMax = <?=$arItem["VALUES"]["MAX"]["VALUE"]?>;
                                    } else if(vMin > vMax) {
                                        $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(vMin);
                                        vMax = vMin;
                                    }


                                    $(".filter-<?=$key?>").slider("values",0,vMin);
                                    $(".filter-<?=$key?>").slider("values",1,vMax);
                                });

                            });
                        </script>
                </div></div>
            <?
                break;
            case "B"://NUMBERS
                /* ?>
                     <div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
                         <i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
                         <div class="bx-filter-input-container">
                             <input
                                 class="min-price"
                                 type="text"
                                 name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                 id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                 value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                 size="5"
                                 onkeyup="smartFilter.keyup(this)"
                             />
                         </div>
                     </div>
                     <div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
                         <i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
                         <div class="bx-filter-input-container">
                             <input
                                 class="max-price"
                                 type="text"
                                 name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                 id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                 value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                 size="5"
                                 onkeyup="smartFilter.keyup(this)"
                             />
                         </div>
                     </div>
                 <?*/
                break;
            case "G"://CHECKBOXES_WITH_PICTURES
                /*?>
                <div class="block-filter__section">
                    <h5 class="block-filter__title"><?= $arItem["NAME"] ?>:</h5>
                    <div class="filter-brands">
                        <?
                        foreach ($arItem["VALUES"] as $val => $ar):?>
                            <div class="filter-brands__item">
                                <input type="checkbox" name="<?= $ar["CONTROL_NAME"] ?>"
                                       id="<?= $ar["CONTROL_ID"] ?>"
                                       value="<?= $ar["HTML_VALUE"] ?>" <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?> />
                                <label for="<?= $ar["CONTROL_ID"] ?>"><img src="<?= $ar["FILE"]["SRC"] ?>"
                                                                           alt="<?= $ar["HTML_VALUE"] ?>"></label>
                            </div>
                        <?endforeach ?>
                    </div>
                </div>
                <?*/
                break;
            case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                /*?>
                    <div class="col-xs-12">
                        <div class="bx-filter-param-btn-block">
                            <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                <input
                                    style="display: none"
                                    type="checkbox"
                                    name="<?=$ar["CONTROL_NAME"]?>"
                                    id="<?=$ar["CONTROL_ID"]?>"
                                    value="<?=$ar["HTML_VALUE"]?>"
                                    <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                />
                                <?
                                $class = "";
                                if ($ar["CHECKED"])
                                    $class.= " bx-active";
                                if ($ar["DISABLED"])
                                    $class.= " disabled";
                                ?>
                                <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
                                <span class="bx-filter-param-btn bx-color-sl">
                                    <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                        <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                    <?endif?>
                                </span>
                                    <span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
                                        if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                            ?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                        endif;?></span>
                                </label>
                            <?endforeach?>
                        </div>
                    </div>
                <?*/
                break;
            case "P"://DROPDOWN
                $checkedItemExist = false;
                /*?>
                    <div class="col-xs-12">
                        <div class="bx-filter-select-container">
                            <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                <div class="bx-filter-select-text" data-role="currentOption">
                                    <?
                                    foreach ($arItem["VALUES"] as $val => $ar)
                                    {
                                        if ($ar["CHECKED"])
                                        {
                                            echo $ar["VALUE"];
                                            $checkedItemExist = true;
                                        }
                                    }
                                    if (!$checkedItemExist)
                                    {
                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                    }
                                    ?>
                                </div>
                                <div class="bx-filter-select-arrow"></div>
                                <input
                                    style="display: none"
                                    type="radio"
                                    name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                    id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                    value=""
                                />
                                <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                    <input
                                        style="display: none"
                                        type="radio"
                                        name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                        id="<?=$ar["CONTROL_ID"]?>"
                                        value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                    />
                                <?endforeach?>
                                <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
                                    <ul>
                                        <li>
                                            <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                            </label>
                                        </li>
                                        <?
                                        foreach ($arItem["VALUES"] as $val => $ar):
                                            $class = "";
                                            if ($ar["CHECKED"])
                                                $class.= " selected";
                                            if ($ar["DISABLED"])
                                                $class.= " disabled";
                                            ?>
                                            <li>
                                                <label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
                                            </li>
                                        <?endforeach?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?*/
                break;
            case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                /*?>
                    <div class="col-xs-12">
                        <div class="bx-filter-select-container">
                            <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                <div class="bx-filter-select-text fix" data-role="currentOption">
                                    <?
                                    $checkedItemExist = false;
                                    foreach ($arItem["VALUES"] as $val => $ar):
                                        if ($ar["CHECKED"])
                                        {
                                            ?>
                                            <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                            <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                        <?endif?>
                                            <span class="bx-filter-param-text">
                                                <?=$ar["VALUE"]?>
                                            </span>
                                            <?
                                            $checkedItemExist = true;
                                        }
                                    endforeach;
                                    if (!$checkedItemExist)
                                    {
                                        ?><span class="bx-filter-btn-color-icon all"></span> <?
                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                    }
                                    ?>
                                </div>
                                <div class="bx-filter-select-arrow"></div>
                                <input
                                    style="display: none"
                                    type="radio"
                                    name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                    id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                    value=""
                                />
                                <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                    <input
                                        style="display: none"
                                        type="radio"
                                        name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                        id="<?=$ar["CONTROL_ID"]?>"
                                        value="<?=$ar["HTML_VALUE_ALT"]?>"
                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                    />
                                <?endforeach?>
                                <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
                                    <ul>
                                        <li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
                                            <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                <span class="bx-filter-btn-color-icon all"></span>
                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                            </label>
                                        </li>
                                        <?
                                        foreach ($arItem["VALUES"] as $val => $ar):
                                            $class = "";
                                            if ($ar["CHECKED"])
                                                $class.= " selected";
                                            if ($ar["DISABLED"])
                                                $class.= " disabled";
                                            ?>
                                            <li>
                                                <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
                                                    <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                        <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                    <?endif?>
                                                    <span class="bx-filter-param-text">
                                                    <?=$ar["VALUE"]?>
                                                </span>
                                                </label>
                                            </li>
                                        <?endforeach?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?*/
                break;
            case "K"://RADIO_BUTTONS
                /*?>
                    <div class="col-xs-12">
                        <div class="radio">
                            <label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
                                <span class="bx-filter-input-checkbox">
                                    <input
                                        type="radio"
                                        value=""
                                        name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
                                        id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                        onclick="smartFilter.click(this)"
                                    />
                                    <span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
                                </span>
                            </label>
                        </div>
                        <?foreach($arItem["VALUES"] as $val => $ar):?>
                            <div class="radio">
                                <label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label" for="<? echo $ar["CONTROL_ID"] ?>">
                                    <span class="bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
                                        <input
                                            type="radio"
                                            value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                            name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                            id="<? echo $ar["CONTROL_ID"] ?>"
                                            <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                            onclick="smartFilter.click(this)"
                                        />
                                        <span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                ?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                            endif;?></span>
                                    </span>
                                </label>
                            </div>
                        <?endforeach;?>
                    </div>
                <?*/
                break;
            case "U"://CALENDAR
                /* ?>
                     <div class="col-xs-12">
                         <div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
                                 <?$APPLICATION->IncludeComponent(
                                     'bitrix:main.calendar',
                                     '',
                                     array(
                                         'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                         'SHOW_INPUT' => 'Y',
                                         'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                         'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                         'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                         'SHOW_TIME' => 'N',
                                         'HIDE_TIMEBAR' => 'Y',
                                     ),
                                     null,
                                     array('HIDE_ICONS' => 'Y')
                                 );?>
                             </div></div>
                         <div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
                                 <?$APPLICATION->IncludeComponent(
                                     'bitrix:main.calendar',
                                     '',
                                     array(
                                         'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                         'SHOW_INPUT' => 'Y',
                                         'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                         'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                         'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                         'SHOW_TIME' => 'N',
                                         'HIDE_TIMEBAR' => 'Y',
                                     ),
                                     null,
                                     array('HIDE_ICONS' => 'Y')
                                 );?>
                             </div></div>
                     </div>
                 <?*/
                break;
            default://CHECKBOXES
                    ?>
                    <div class="filter-brand<?if($i > 1):?> hidden-filter" style="display: none;<?endif?>"><a href="" class="sh-all-prop" <?if($i > 1):?>data-prop="close"<?else:?>data-prop="stop"<?endif?>><span><?=$arItem["NAME"]?></span><?if($i > 1):?><svg xmlns="http://www.w3.org/2000/svg" width="7" height="10"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow_down"></use></svg><?endif?></a><div class="row" <?if($i > 1):?>style="display: none"<?endif?>>
                            <?foreach($arItem["VALUES"] as $val => $ar):?>
                                <div class="brand-checkbox-area col-lg-2 col-md-2 col-xs-6"><div class="brand-checkbox">
                                    <input type="checkbox"value="<? echo $ar["HTML_VALUE"] ?>" name="<? echo $ar["CONTROL_NAME"] ?>" id="<? echo $ar["CONTROL_ID"] ?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>><label data-role="label_<?=$ar["CONTROL_ID"]?>" for="<? echo $ar["CONTROL_ID"] ?>"><?=$ar["VALUE"];?></label>
                                </div></div>
                            <?endforeach;?>

                    </div></div>
                <?
                //}

        }

    }
    ?>
    <?/*<input type="submit" id="set_filter" name="set_filter" class="btn btn_active" value="ПОКАЗАТЬ" />
    <input type="submit" id="del_filter" name="del_filter" value="Сбросить фильтры">*/?>

    <div class="price-setting"><div class="row">
    <?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
    {
    $key = $arItem["ENCODED_ID"];
    if(isset($arItem["PRICE"])):
    if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
    continue;

    if (floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) == 0) {
    $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
    }
    if (floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) == 0) {
    $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
    }
    ?>
        <span>Цена</span>
            <div class="filter-price-area col-lg-4 col-md-4 col-xs-12">
                <input type="text" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>">
                <span class="razdel">-</span>
                <input type="text" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>">
                <span class="rubl">i</span>
                <div class="filter-price"></div>
            </div>
            <script>
                $(document).ready(function() {
                    $('.filter-price').slider({
                        min: <?=$arItem["VALUES"]["MIN"]["VALUE"]?>,
                        max: <?=$arItem["VALUES"]["MAX"]["VALUE"]?>,
                        values: [<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>, <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>],
                        range: true,
                        slide: function (event, ui) {
                            $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(ui.values[0]);
                            $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val(ui.values[1]);
                        }
                    });

                    $('#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>, #<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>').on('keydown', function(e){
                        if(e.key.length == 1 && e.key.match(/[^0-9'".]/)){
                            return false;
                        };
                    });

                    $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>, #<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").change(function(){
                        var vMin = $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val();
                        var vMax = $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val();

                        if(vMin < <?=$arItem["VALUES"]["MIN"]["VALUE"]?>){
                            $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(<?=$arItem["VALUES"]["MIN"]["VALUE"]?>);
                            vMin = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
                        } else if(vMin > vMax) {
                            $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(vMax);
                            vMin = vMax;
                        }

                        if(vMax > <?=$arItem["VALUES"]["MAX"]["VALUE"]?>){
                            $("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val(<?=$arItem["VALUES"]["MAX"]["VALUE"]?>);
                            vMax = <?=$arItem["VALUES"]["MAX"]["VALUE"]?>;
                        } else if(vMin > vMax) {
                            $("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(vMin);
                            vMax = vMin;
                        }


                        $(".filter-price").slider("values",0,vMin);
                        $(".filter-price").slider("values",1,vMax);
                    });

                });
            </script>
<?endif;
}?>

            <div class="link-show col-lg-4 col-md-4 col-xs-12">
                <input type="submit" id="set_filter" name="set_filter" value="ПОКАЗАТЬ" />
            </div>
            <?if($i > 1):?>
            <div class="advanced-search col-lg-4 col-md-4 col-xs-12">
                <a href="" id="show-all-prop">Расширенный поиск</a>
            </div>
            <?endif?>
        </div>
    </div>
</form>
</div>
<?endif?>
