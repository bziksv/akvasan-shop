<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?if(count($arResult["ITEMS"]) != $count_prop) :?>
<div class="container-filter">
	<div class="filter-preload"></div>
	<div class="row">
	
    <div class="filter-area">

        <form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
            <div class="wrapp-form">
                <?foreach($arResult["HIDDEN"] as $arItem):?>
                    <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
                <?endforeach;?>

                <?
                //brands
                foreach($arResult["ITEMS"] as $key=>$arItem) {
                    if (empty($arItem["VALUES"]) || $arItem["NAME"] != "Бренд") {
                        continue;
                    }
                    ?>
                    <div class="left-filter ">
                        <div class="title-filter"><a href=""><?=$arItem["NAME"]?></a></div>
                        <div class="body-filter">
                            <?foreach($arItem["VALUES"] as $val => $ar):?>
                                <div class="container-checkbox">
                                    <input type="checkbox" onclick="smartFilter.click(this)" name="<?=$ar["CONTROL_NAME"]?>" id="<?=$ar["CONTROL_ID"]?>" <?echo $ar["CHECKED"]? 'checked="checked"': ''?> value="<?=$ar["HTML_VALUE"]?>" />
                                    <label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"]?></label>
                                </div>
                        <?endforeach?>
                        </div>
                    </div>
                <?
                }
                ?>

                <div class="center-filter">
                    <?if(CSite::InDir('/catalog/smesiteli/')):?>
                        <?foreach($arResult["ITEMS"] as $key=>$arItem) :
                            if (empty($arItem["VALUES"]) || $arItem["NAME"] != "Назначение") {
                                continue;
                            }
                            ?>
                                <div class="workarea-filter">        
                                    <div class="title-filter"><a href="" ><?=$arItem["NAME"]?></a> <span><!-- (?) --></span></div>                            
                                    <div class="body-filter">
                                    <?foreach($arItem["VALUES"] as $val => $ar):?>
                                        <div class="container-checkbox">
                                            <input type="checkbox" onclick="smartFilter.click(this)" value="<?=$ar["HTML_VALUE"]?>" name="<?=$ar["CONTROL_NAME"]?>" id="<?=$ar["CONTROL_ID"]?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?> />
                                            <label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
                                        </div>
                                    <?endforeach;?>
                                    </div>
                                        
                                </div>
                            <?endforeach?>
                    <?endif;?>

                    <?if(CSite::InDir('/catalog/vanny/')):?>
                        <?foreach($arResult["ITEMS"] as $key=>$arItem) :
                            if (empty($arItem["VALUES"]) || $arItem["NAME"] != "Материал") {
                                continue;
                            }
                            ?>
                                <div class="workarea-filter">        
                                    <div class="title-filter"><a href="" ><?=$arItem["NAME"]?></a> <span><!-- (?) --></span></div>                            
                                    <div class="body-filter">
                                    <?foreach($arItem["VALUES"] as $val => $ar):?>
                                        <div class="container-checkbox">
                                            <input type="checkbox" onclick="smartFilter.click(this)" value="<?=$ar["HTML_VALUE"]?>" name="<?=$ar["CONTROL_NAME"]?>" id="<?=$ar["CONTROL_ID"]?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?> />
                                            <label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
                                        </div>
                                    <?endforeach;?>
                                    </div>
                                        
                                </div>
                            <?endforeach?>
                        <?else:?>
                        <?foreach($arResult["ITEMS"] as $key=>$arItem) :
                        if (empty($arItem["VALUES"]) || $arItem["NAME"] != "Тип") {
                            continue;
                        }
                        ?>
                            <div class="workarea-filter">        
                                <div class="title-filter"><a href="" ><?=$arItem["NAME"]?></a> <span><!-- (?) --></span></div>                            
                                <div class="body-filter">
                                <?foreach($arItem["VALUES"] as $val => $ar):?>
                                    <div class="container-checkbox">
                                        <input type="checkbox" onclick="smartFilter.click(this)" value="<?=$ar["HTML_VALUE"]?>" name="<?=$ar["CONTROL_NAME"]?>" id="<?=$ar["CONTROL_ID"]?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?> />
                                        <label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
                                    </div>
                                <?endforeach;?>
                                </div>
                                    
                            </div>
                        <?endforeach?>
                    <?endif;?>
                </div>
                
                <div class="right-filter">
                    <?
                    //prices
                    foreach($arResult["ITEMS"] as $key=>$arItem) {
                        $key = $arItem["ENCODED_ID"];
                        if (isset($arItem["PRICE"])) {
                            if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                            continue;

                            if (floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) == 0) {
                                $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
                            }
                            if (floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) == 0) {
                                $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
                            }?>
                            <div class="container-filter-price">
                                <div class="title-filter"><a href="#">Цена</a> <span>(руб.)</span></div>
                                    
                                <div class="filter-price-area">
                                        <div class="container-input">
                                            <input type="text" onkeyup="smartFilter.keyup(this)" class="input-one" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  placeholder="<?=(int)$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" value="">
                                            <input type="text" onkeyup="smartFilter.keyup(this)" class="input-two" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"  id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"  placeholder="<?=(int)$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" value="">
                                        </div>
                                        <div class="filter-price"></div>
                                </div>
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
											
											smartFilter.reload($("#<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").get(0));
											smartFilter.reload($("#<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").get(0));
                                          
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
                        <?
                        }
                    }?>

                    
                    <div class="block-filter"><div class="row">
                    <div class="right-column col-lg-12 col-md-12">
                    <div class="row">
                    <div class="wrapp-left col-lg-6 col-md-6">
                    <div class="wrapp-block">
                    
                
                        <?
                        $max_count=0;
                        foreach($arResult["ITEMS"] as $key=>$arItem){
                            if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]) || $arItem["NAME"] == "Бренд" || $arItem["NAME"] == "Тип") {
                                    continue;
                                }

                            if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)) {
                                continue;
                            }

                            if(CSite::InDir('/catalog/smesiteli/') && ($arItem["NAME"] == "Назначение")){
                                unset($arResult['ITEMS'][$key]);
                            }
                            
                            $max_count++;
                        }

                        $i=0;
                        $left=(int)($max_count/2) + $max_count%2;
                        $right=$max_count-$left;
                        $count=1;
                        //not prices and brands
						
                        foreach($arResult["ITEMS"] as $key=>$arItem) {
							
                            if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]) || $arItem["NAME"] == "Бренд" || $arItem["NAME"] == "Тип") {
                                continue;
                            }

                            if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)) {
                                continue;
                            }

                            $i++;
							
                        /*Тут разделение на столбы*/

                        ?>

                        <?if($count-1 == 4 && ($count-1 <($max_count + ($left + 4)) + $max_count%4)):?>
                            <div class="drop-filter">
                        <?endif;?>        

                        <?if(((int)($max_count/2) + $max_count%2) == $count-1):?>
                            </div></div></div><div class="wrapp-right col-lg-6 col-md-6"><div class="wrapp-block">
                        <?endif?>

                        <?if(($count-1 == $left + 4) && ($count-1 > $left)):?>
                            </div><div class="drop-filter">
                        <?endif;?>

                       

                        <?
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
                                        <div class="workarea-filter">        
                                            <div class="title-filter"><a href="" data-close='yes'><?=$arItem["NAME"]?></a> <span><!-- (?) --></span></div>                            
                                            <div class="body-filter">
                                                <input type="text" onkeyup="smartFilter.keyup(this)" class="only-numeric numeric-min" data-value="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"  value="" placeholder="<?echo number_format($arItem["VALUES"]["MIN"]["HTML_VALUE"],2,'.', '')?>"/>
                                                <span>&mdash;</span>
                                                <input type="text" onkeyup="smartFilter.keyup(this)" class="only-numeric numeric-max" data-value="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" value="" placeholder="<?echo  number_format($arItem["VALUES"]["MAX"]["HTML_VALUE"],1,'.', '')?>" />
                                            </div>
                                                
                                        </div>
                                    
                                    <?
                                    break;
                                    default: //CHECKBOXES
                                    ?>
                                    
                                    <?if(CSite::InDir('/catalog/vanny/') && ($arItem["NAME"] == "Материал")):?>
                                        <?continue;?>
                                    <?endif;?>

                                    <?/*if(CSite::InDir('/catalog/smesiteli/') && ($arItem["NAME"] == "Назначение")):?>
                                        <?continue;?>
                                    <?endif;*/?>

                                        <div class="workarea-filter">        
                                            <div class="title-filter"><a href="" data-close='yes'><?=$arItem["NAME"]?></a> <span><!-- (?) --></span></div>                            
                                            <div class="body-filter">
                                            <?foreach($arItem["VALUES"] as $val => $ar):?>
                                                <div class="container-checkbox">
                                                    <input type="checkbox" onclick="smartFilter.click(this)" value="<?=$ar["HTML_VALUE"]?>" name="<?=$ar["CONTROL_NAME"]?>" id="<?=$ar["CONTROL_ID"]?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?> />
                                                    <label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
                                                </div>
                                            <?endforeach;?>
                                            </div>
                                                
                                        </div>
                                                    
                                    <? 
                            }
                            $count++;
                        }

                        ?>
                    <?/*</div></div></div></div></div></div></div>*/?>
                    <?if($max_count == 4):?>
                        </div></div></div></div></div></div>

                    <?else:?>
                        </div></div></div></div></div></div></div>
                    <?endif?>

                </div>
                <div class="block-search">
                    <?if($i > 4):?>
                        <a href="" class="advanced-search">Расширенный поиск</a>
                    <?endif;?>
                    <button class="filter-reset hidden-xs hidden-sm" type="submit" id="del_filter" name="del_filter">Сбросить фильтр</button>
                    <div class="right-block">
                        <?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                        <input type="submit" id="set_filter" name="set_filter" class="button-show" value="Показать" style="border:none;" />
                        <button class="filter-reset hidden-lg hidden-md" type="submit" id="del_filter" name="del_filter">Сбросить фильтр</button>
                    </div>      
                </div>
				
				<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
			
					<span class="arrow"></span>
					<br/>
					<a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
				</div>
        </form>
</div></div></div>

<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
<?endif?>