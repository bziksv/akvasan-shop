<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="ci-title"<?if(!in_array($arResult["USER_VALS"]["DELIVERY_ID"],$arParams["CZ_DELIVERY_ADRESS"])):?>style="display:none"<?endif?>>Информация о доставке</div>
<ul <?if(!in_array($arResult["USER_VALS"]["DELIVERY_ID"],$arParams["CZ_DELIVERY_ADRESS"])):?>style="display:none"<?endif?>>
<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $arProperties):?>
<?if($arProperties["CODE"] == "DELIVERY_TIME"):?>
	<?continue;?>
<?endif;?>
<li>
    <p><?=$arProperties["NAME"]?></p>
    <?if ($arProperties["TYPE"] == "LOCATION")
		{
			?>
				<?
				$value = 0;
				if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
				{
					foreach ($arProperties["VARIANTS"] as $arVariant)
					{
						if ($arVariant["SELECTED"] == "Y")
						{
							$value = $arVariant["ID"];
							break;
						}
					}
				}

				// here we can get '' or 'popup'
				// map them, if needed
				if(CSaleLocation::isLocationProMigrated())
				{
					//$locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
					//$locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
					$locationTemplateP = 'search';
				}
				?>

				<?if($locationTemplateP == 'steps'):?>
					<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
				<?endif?>

				<?CSaleLocation::proxySaleAjaxLocationsComponent(array(
					"AJAX_CALL" => "N",
					"COUNTRY_INPUT_NAME" => "COUNTRY",
					"REGION_INPUT_NAME" => "REGION",
					"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
					"CITY_OUT_LOCATION" => "Y",
					"LOCATION_VALUE" => $value,
					"ORDER_PROPS_ID" => $arProperties["ID"],
					"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
					"SIZE1" => $arProperties["SIZE1"],
				),
					array(
						"ID" => $value,
						"CODE" => "",
						"SHOW_DEFAULT_LOCATIONS" => "Y",

						// function called on each location change caused by user or by program
						// it may be replaced with global component dispatch mechanism coming soon
						"JS_CALLBACK" => "submitFormProxy",

						// function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
						// it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
						"JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

						// an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
						// it may be replaced with global component dispatch mechanism coming soon
						"JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

						"DISABLE_KEYBOARD_INPUT" => "Y",
						"PRECACHE_LAST_LEVEL" => "Y",
						"PRESELECT_TREE_TRUNK" => "Y",
						"SUPPRESS_ERRORS" => "Y"
					),
					$locationTemplateP,
					true,
					'location-block-wrapper'
				)?>
				<?if(CSaleLocation::isLocationProEnabled()):?>

	<?
	$propertyAttributes = array(
		'type' => $arProperties["TYPE"],
		'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form' // value taken from property DEFAULT_VALUE or it`s a user-typed value?
	);

	if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
		$propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

	if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
		$propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

	if($arProperties['IS_ZIP'] == 'Y')
		$propertyAttributes['isZip'] = true;
	?>

		<script>

			<?// add property info to have client-side control on it?>
			(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
					'id' => intval($arProperties["ID"]),
					'attributes' => $propertyAttributes
				))?>);

		</script>
	<?endif?>
				<input type="hidden" id="location_prop" value="[name='ORDER_PROP_<?=$arProperties["ID"]?>']" />
			<?
		} else {?>
	<?
	$phone_tag = "";
    if(in_array($arProperties["ID"],$arParams["CZ_REQUIRED_DELIVERY_PROPS"])) {
        $validate = 'data-cz-validated-type="data" data-cz-validated-group="group_order_delivery" data-cz-validated-msg="* Необходимо заполнить поле '.$arProperties["NAME"].'"';
    } else {
        $validate = '';
    }
	?>
    <input class="itext" type="text" <?=$phone_tag?> <?=$validate?> value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>">
                        <?}?>
</li>
<?endforeach;?>
</ul>

<?if(in_array($arResult["USER_VALS"]["DELIVERY_ID"],$arParams["CZ_DELIVERY_ADRESS"])):?>
	<input type="hidden" id="delivery_adress_valid" value="Y" />
<?else:?>
	<input type="hidden" id="delivery_adress_valid" value="N" />
<?endif?>


<div class="ci-title" <?if(!in_array($arResult["USER_VALS"]["DELIVERY_ID"],$arParams["CZ_DELIVERY_ADRESS"])):?>style="display:none"<?endif?>>Срок доставки</div>
<ul class="is-rlist delivery" <?if(!in_array($arResult["USER_VALS"]["DELIVERY_ID"],$arParams["CZ_DELIVERY_ADRESS"])):?>style="display:none"<?endif?>>
	<li class="is-radio">
		<p>
			<input type="radio" name="delivery" id="delivery_express" value="express" class="css-checkbox"/>
			<label for="delivery_express">Экспресс доставка (1-2 дня)</label>
			<input type="hidden" value="Экспресс доставка (1-2 дня)">
		</p>
	</li>
	<li class="is-radio">
		<p>
			<input type="radio" name="delivery" id="delivery_standart" value="standart" class="css-checkbox"/>
			<label for="delivery_standart">Стандартная доставка (3-7 дней)</label>
			<input type="hidden" value="Стандартная доставка (3-7 дней)">
		</p>
	</li>
</ul>
<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $arProperties):?>
	<?if($arProperties["CODE"] == "DELIVERY_TIME"):?>
		<!--<?=$arProperties["NAME"]?>-->
		<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="<?=$arProperties["VALUE"]?>"/>
	<?endif;?>
<?endforeach;?>
	

