<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)  die();
$this->setFrameMode(false);

if (!empty($arResult["ERRORS"]))
	ShowError(implode("<br />", $arResult["ERRORS"]));

if (strlen($arResult["MESSAGE"]) > 0)
	ShowNote($arResult["MESSAGE"]);
?>
<form id="<?=$arParams["FORM_ID"]?>" name="<?=$arParams["FORM_ID"]?>" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
	<div style="display:none;"><input name="czebra_control" type="text" value="" /></div>
	<?=bitrix_sessid_post()?>
	<?if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])) :
		foreach ($arResult["PROPERTY_LIST"] as $propertyID) :?>
			<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"]?><?//Названание поля?>
			<?if(in_array($propertyID, $arResult["PROPERTY_REQUIRED"])):?>*<?endif?>
			<?

			if ($arParams["VALIDATED"] == "Y") {
				$validation = "";
				if (in_array($propertyID,$arParams["PROPERTY_CODES_REQUIRED_CHECKBOX"]))
					$validation = ' data-cz-validated-type="checkbox"';
				elseif (in_array($propertyID,$arParams["PROPERTY_CODES_REQUIRED_EMAIL"]))
					$validation = ' data-cz-validated-type="email"';
				elseif (in_array($propertyID,$arParams["PROPERTY_CODES_REQUIRED"]))
					$validation = ' data-cz-validated-type="data"';
				if (strlen($validation) > 0) {
					$validation .= 'data-cz-validated-group="'.$arParams["FORM_ID"].'_group"';
					
					$strTempError = GetMessage("CZEBRA.FORM.".$arParams["FORM_ID"]."_".$propertyID); 
					if (strlen($strTempError) > 0)
						$validation .= 'data-cz-validated-msg="* '.$strTempError.'"'; 
					else
						$validation .= 'data-cz-validated-msg="* Необходимо заполнить поле '.$arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"].'"'; 
				}
			}

			if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"])
				$inputType = "USER_TYPE";
			else
				$inputType = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];

			switch ($inputType):
				//Тип свойства строка/число
				case "S":
				case "N":
						if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) 
							$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
						else
							$value = "";
					?>
					<input type="text" name="PROPERTY[<?=$propertyID?>][0]" value="<?=$value?>"<?=$validation?> />
					<?
				break;
				//Тип свойства HTML/текст
				case "USER_TYPE": 
					if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) {
						$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"] : $arResult["ELEMENT"][$propertyID];
						$description = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["DESCRIPTION"] : "";
					} else {
						$value = "";
						$description = "";
					}
					?>
					<textarea name="PROPERTY[<?=$propertyID?>][0]"<?=$validation?>></textarea>
					<?
				break;
				//Тип свойства файл
				case "F":
					$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE"] : $arResult["ELEMENT"][$propertyID];
					?>
					<input type="hidden" name="PROPERTY[<?=$propertyID?>][<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE_ID"] : 0?>]" value="<?=$value?>" />
					<input type="file" size="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]?>"  name="PROPERTY_FILE_<?=$propertyID?>_<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE_ID"] : 0?>" />
					<?
				break;
				//Тип свойсвтва Список
				case "L":
					if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["LIST_TYPE"] == "C")
						$type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "checkbox" : "radio";
					else
						$type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "multiselect" : "dropdown";

					switch ($type):
						case "checkbox":
						case "radio":
							foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum) {
								$checked = false;
								if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) {
									if (is_array($arResult["ELEMENT_PROPERTIES"][$propertyID])) {
										foreach ($arResult["ELEMENT_PROPERTIES"][$propertyID] as $arElEnum) {
											if ($arElEnum["VALUE"] == $key) {
												$checked = true;
												break;
											}
										}
									}
								} else {
									if ($arEnum["DEF"] == "Y") 
										$checked = true;
								}

								?>
									<input <?=$validation?> type="<?=$type?>" name="PROPERTY[<?=$propertyID?>]<?=$type == "checkbox" ? "[".$key."]" : ""?>" value="<?=$key?>" id="property_<?=$key?>"<?=$checked ? " checked=\"checked\"" : ""?> /><label for="property_<?=$key?>"><?=$arEnum["VALUE"]?></label>
								<?
							}
						break;
						case "dropdown":
						case "multiselect":
						?>
							<select name="PROPERTY[<?=$propertyID?>]<?=$type=="multiselect" ? "[]\" size=\"".$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]."\" multiple=\"multiple" : ""?>">
								<option value=""><?echo GetMessage("CT_BIEAF_PROPERTY_VALUE_NA")?></option>
									<?
										if (intval($propertyID) > 0)
											$sKey = "ELEMENT_PROPERTIES";
										else 
											$sKey = "ELEMENT";

										foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum) {
											$checked = false;
											if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) {
												foreach ($arResult[$sKey][$propertyID] as $elKey => $arElEnum) {
													if ($key == $arElEnum["VALUE"]) {
														$checked = true;
														break;
													}
												}
											} else {
												if ($arEnum["DEF"] == "Y") 
													$checked = true;
											}
											?>
									<option value="<?=$key?>" <?=$checked ? " selected=\"selected\"" : ""?>><?=$arEnum["VALUE"]?></option>
											<?
										}
									?>
							</select>
									<?
						break;
					endswitch;
				break;
			endswitch;?>
		<?endforeach;?>
		
		<?if($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0):?>
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			<input type="text" name="captcha_word" maxlength="50" value="">
		<?endif?>
	<?endif?>
	<?$btmName = strlen($arParams["MSG_BTN"]) > 0 ? $arParams["MSG_BTN"] : GetMessage("CZEBRA.FORM.BTN_SUBMIT");?>
	<p>Нажимая на кнопку "<?=$btmName?>", Вы соглашаетесь на обработку персональных данных в соответствии с <a href="/legal/personal-data/" target="_blank">политикой конфеденциальности</a></p>
	<input type="submit" id="<?=$arParams["FORM_ID"]?>_sibmit" name="czebra_submit" value="<?=$btmName?>" />
</form>
<?if($arParams["VALIDATED"] == "Y"):?>
<script>
	cz_validated.runBtn('<?=$arParams["FORM_ID"]?>_sibmit', '<?=$arParams["FORM_ID"]?>_group');
</script>
<?endif?>