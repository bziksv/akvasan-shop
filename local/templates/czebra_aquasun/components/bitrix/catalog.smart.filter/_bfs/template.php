<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
    <?foreach($arResult["HIDDEN"] as $arItem):?>
        <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
    <?endforeach;?>

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
                        <div class="block-filter__section">
                            <h5 class="block-filter__title">Цена (₽):</h5>
                            <div class="filter-range">
                                <div class="filter-range__row">
                                    <input type="text" class="only-numeric" data-value="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"  size="5" />
                                    <span>&mdash;</span>
                                    <input type="text" class="only-numeric" data-value="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"  value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"  size="5" />
                                </div>
                                <div class="filter-range__range">
                                    <div class="slider-price"></div>
                                </div>
                            </div>
                        </div>
                    <?endif;
                }

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

                    $arCur = current($arItem["VALUES"]);
                    switch ($arItem["DISPLAY_TYPE"]) {
                        case "A"://NUMBERS_WITH_SLIDER
                            if (floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) == 0) {
                                $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
                            }
                            if (floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) == 0) {
                                $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
                            }
                            ?>
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
                        <?
                            /*   ?>
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

                               <div class="col-xs-10 col-xs-offset-1 bx-ui-slider-track-container">
                                   <div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
                                       <?
                                       $precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
                                       $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
                                       $value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
                                       $value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
                                       $value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
                                       $value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
                                       $value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                                       ?>
                                       <div class="bx-ui-slider-part p1"><span><?=$value1?></span></div>
                                       <div class="bx-ui-slider-part p2"><span><?=$value2?></span></div>
                                       <div class="bx-ui-slider-part p3"><span><?=$value3?></span></div>
                                       <div class="bx-ui-slider-part p4"><span><?=$value4?></span></div>
                                       <div class="bx-ui-slider-part p5"><span><?=$value5?></span></div>

                                       <div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
                                       <div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
                                       <div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
                                       <div class="bx-ui-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
                                           <a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
                                           <a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
                                       </div>
                                   </div>
                               </div>
                           <?
                           $arJsParams = array(
                               "leftSlider" => 'left_slider_'.$key,
                               "rightSlider" => 'right_slider_'.$key,
                               "tracker" => "drag_tracker_".$key,
                               "trackerWrap" => "drag_track_".$key,
                               "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                               "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                               "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                               "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                               "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                               "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                               "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
                               "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                               "precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
                               "colorUnavailableActive" => 'colorUnavailableActive_'.$key,
                               "colorAvailableActive" => 'colorAvailableActive_'.$key,
                               "colorAvailableInactive" => 'colorAvailableInactive_'.$key,
                           );
                           ?>
                               <script type="text/javascript">
                                   BX.ready(function(){
                                       window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
                                   });
                               </script>
                           <?*/
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
                            ?>
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
                            <?
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

                            if (count($arItem["VALUES"]) == 1) {
                                ?>
                                <div class="block-filter__section">
                                    <div class="filter-type">
                                        <?
                                        foreach ($arItem["VALUES"] as $val => $ar):?>
                                            <div class="filter-type__item">
                                                <input type="checkbox" class="filter-checkbox"
                                                       value="<? echo $ar["HTML_VALUE"] ?>"
                                                       name="<? echo $ar["CONTROL_NAME"] ?>"
                                                       id="<? echo $ar["CONTROL_ID"] ?>" <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>/>
                                                <label for="<? echo $ar["CONTROL_ID"] ?>"><?= $arItem["NAME"] ?></label>
                                            </div>
                                        <?endforeach; ?>
                                    </div>
                                </div>
                                <?
                            }

                    }

                }
                ?>
    <input type="submit" id="set_filter" name="set_filter" class="btn btn_active" value="ПОКАЗАТЬ" />
    <input type="submit" id="del_filter" name="del_filter" value="Сбросить фильтры">
</form>
