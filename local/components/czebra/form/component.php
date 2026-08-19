<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
	die();
$this->setFrameMode(false);

$parnterPrefix = "CZEBRA.FORM.";

if (!CModule::IncludeModule("iblock")) {
	ShowError(GetMessage($parnterPrefix."CC_BIEAF_IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

$arResult["ERRORS"] = array();

if (!is_array($arParams["PROPERTY_CODES"])) {
	$arParams["PROPERTY_CODES"] = array();
} else {
	foreach ($arParams["PROPERTY_CODES"] as $i => $k)
		if (strlen($k) <= 0)
			unset($arParams["PROPERTY_CODES"][$i]);
}
$arParams["PROPERTY_CODES_REQUIRED"] = is_array($arParams["PROPERTY_CODES_REQUIRED"]) ? $arParams["PROPERTY_CODES_REQUIRED"] : array();
foreach ($arParams["PROPERTY_CODES_REQUIRED"] as $key => $value)
	if (strlen(trim($value)) <= 0)
		unset($arParams["PROPERTY_CODES_REQUIRED"][$key]);

$arParams["USER_MESSAGE_ADD"] = trim($arParams["USER_MESSAGE_ADD"]);
if (strlen($arParams["USER_MESSAGE_ADD"]) <= 0)
	$arParams["USER_MESSAGE_ADD"] = GetMessage($parnterPrefix."IBLOCK_USER_MESSAGE_ADD_DEFAULT");

$arResult["PROPERTY_LIST"] = array();
$arResult["PROPERTY_LIST_FULL"] = array();
$arResult["PROPERTY_REQUIRED"] = is_array($arParams["PROPERTY_CODES_REQUIRED"]) ? $arParams["PROPERTY_CODES_REQUIRED"] : array();
$rsIBLockPropertyList = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while ($arProperty = $rsIBLockPropertyList->GetNext()) {
	if ($arProperty["PROPERTY_TYPE"] == "L") {
		$rsPropertyEnum = CIBlockProperty::GetPropertyEnum($arProperty["ID"]);
		$arProperty["ENUM"] = array();
		while ($arPropertyEnum = $rsPropertyEnum->GetNext()) {
			$arProperty["ENUM"][$arPropertyEnum["ID"]] = $arPropertyEnum;
		}
	}

	if ($arProperty["PROPERTY_TYPE"] == "T") {
		if (empty($arProperty["COL_COUNT"])) 
			$arProperty["COL_COUNT"] = "30";
		if (empty($arProperty["ROW_COUNT"]))
			$arProperty["ROW_COUNT"] = "5";
	}

	if (strlen($arProperty["USER_TYPE"]) > 0 ) {
		$arUserType = CIBlockProperty::GetUserType($arProperty["USER_TYPE"]);
		if (array_key_exists("GetPublicEditHTML", $arUserType))
			$arProperty["GetPublicEditHTML"] = $arUserType["GetPublicEditHTML"];
		else
			$arProperty["GetPublicEditHTML"] = false;
	} else {
		$arProperty["GetPublicEditHTML"] = false;
	}

	if (in_array($arProperty["ID"], $arParams["PROPERTY_CODES"]))
		$arResult["PROPERTY_LIST"][] = $arProperty["ID"];

	$arResult["PROPERTY_LIST_FULL"][$arProperty["ID"]] = $arProperty;
}

//Add new elements
if (check_bitrix_sessid() && !empty($_REQUEST["czebra_submit"]) && empty($_REQUEST["czebra_control"])) {
	$arProperties = $_REQUEST["PROPERTY"];

	$arUpdateValues = array();
	$arUpdatePropertyValues = array();

	// process properties list
	foreach ($arParams["PROPERTY_CODES"]  as $i => $propertyID) {
		$arPropertyValue = $arProperties[$propertyID];
		if (intval($propertyID) > 0) {
			// for non-file properties
			if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] != "F") {
				// for multiple properties
				if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y") {
					$arUpdatePropertyValues[$propertyID] = array();

					if (!is_array($arPropertyValue)) {
						$arUpdatePropertyValues[$propertyID][] = $arPropertyValue;
					} else {
						foreach ($arPropertyValue as $key => $value) {
							if (
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "L" && intval($value) > 0
								||
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] != "L" && !empty($value)
								) {
								$arUpdatePropertyValues[$propertyID][] = htmlspecialcharsEx($value);
							}
						}
					}
				} else {
					if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] != "L")
						$arUpdatePropertyValues[$propertyID] = $arPropertyValue[0];
					else
						$arUpdatePropertyValues[$propertyID] = $arPropertyValue;
				}
			} else {
				// for file properties
				$arUpdatePropertyValues[$propertyID] = array();
				foreach ($arPropertyValue as $key => $value){
					$arFile = $_FILES["PROPERTY_FILE_".$propertyID."_".$key];
					$arFile["del"] = $_REQUEST["DELETE_FILE"][$propertyID][$key] == "Y" ? "Y" : "";
					$arUpdatePropertyValues[$propertyID][$key] = $arFile;
				}

				if (empty($arUpdatePropertyValues[$propertyID]))
					unset($arUpdatePropertyValues[$propertyID]);
			}
		} else {
			// for "virtual" properties
			if ($propertyID == "IBLOCK_SECTION") {
				if (!is_array($arProperties[$propertyID]))
					$arProperties[$propertyID] = array($arProperties[$propertyID]);
				$arUpdateValues[$propertyID] = $arProperties[$propertyID];

				if ($arParams["LEVEL_LAST"] == "Y" && is_array($arUpdateValues[$propertyID])) {
					foreach ($arUpdateValues[$propertyID] as $section_id) {
						$rsChildren = CIBlockSection::GetList(
							array("SORT" => "ASC"),
							array(
								"IBLOCK_ID" => $arParams["IBLOCK_ID"],
								"SECTION_ID" => $section_id,
							),
							false,
							array("ID")
						);
						if ($rsChildren->SelectedRowsCount() > 0) {
							$arResult["ERRORS"][] = GetMessage($parnterPrefix."IBLOCK_ADD_LEVEL_LAST_ERROR");
							break;
						}
					}
				}

				if ($arParams["MAX_LEVELS"] > 0 && count($arUpdateValues[$propertyID]) > $arParams["MAX_LEVELS"]) {
					$arResult["ERRORS"][] = str_replace("#MAX_LEVELS#", $arParams["MAX_LEVELS"], GetMessage($parnterPrefix."IBLOCK_ADD_MAX_LEVELS_EXCEEDED"));
				}
			} else {
				if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "F") {
					$arFile = $_FILES["PROPERTY_FILE_".$propertyID."_0"];
					$arFile["del"] = $_REQUEST["DELETE_FILE"][$propertyID][0] == "Y" ? "Y" : "";
					$arUpdateValues[$propertyID] = $arFile;
				} elseif($arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "HTML") {
					if ($propertyID == "DETAIL_TEXT")
						$arUpdateValues["DETAIL_TEXT_TYPE"] = "html";
					if ($propertyID == "PREVIEW_TEXT")
						$arUpdateValues["PREVIEW_TEXT_TYPE"] = "html";
					$arUpdateValues[$propertyID] = $arProperties[$propertyID][0];
				} else {
					if ($propertyID == "DETAIL_TEXT")
						$arUpdateValues["DETAIL_TEXT_TYPE"] = "text";
					if ($propertyID == "PREVIEW_TEXT")
						$arUpdateValues["PREVIEW_TEXT_TYPE"] = "text";
					$arUpdateValues[$propertyID] = $arProperties[$propertyID][0];
				}
			}
		}
	}

	// check required properties
	foreach ($arParams["PROPERTY_CODES_REQUIRED"] as $key => $propertyID) {
		$bError = false;
		$propertyValue = intval($propertyID) > 0 ? $arUpdatePropertyValues[$propertyID] : $arUpdateValues[$propertyID];

		if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] != "")
			$arUserType = CIBlockProperty::GetUserType($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"]);
		else
			$arUserType = array();

		//Files check
		if ($arResult["PROPERTY_LIST_FULL"][$propertyID]['PROPERTY_TYPE'] == 'F') {
			$bError = true;
			if (is_array($propertyValue)) {
				if (array_key_exists("tmp_name", $propertyValue) && array_key_exists("size", $propertyValue)) {
					if ($propertyValue['size'] > 0)
						$bError = false;
				} else {
					foreach ($propertyValue as $arFile) {
						if ($arFile['size'] > 0) {
							$bError = false;
							break;
						}
					}
				}
			}
		} elseif (array_key_exists("GetLength", $arUserType)) {
			if (strlen($propertyValue["VALUE"]) <= 0)
				$bError = true;

		} elseif ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" || $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "L") {
			if (is_array($propertyValue)) {
				$bError = true;
				foreach ($propertyValue as $value) {
					if (strlen($value) > 0) {
						$bError = false;
						break;
					}
				}
			} elseif (strlen($propertyValue) <= 0) {
				$bError = true;
			}
		} elseif (is_array($propertyValue) && array_key_exists("VALUE", $propertyValue)) {
			if (strlen($propertyValue["VALUE"]) <= 0)
				$bError = true;
		}
		elseif (!is_array($propertyValue)) {
			if (strlen($propertyValue) <= 0)
				$bError = true;
		}

		if ($bError) {
			$arResult["ERRORS"][] = str_replace("#PROPERTY_NAME#", intval($propertyID) > 0 
						? $arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"] : 
						(!empty($arParams["CUSTOM_TITLE_".$propertyID]) ? $arParams["CUSTOM_TITLE_".$propertyID] : GetMessage("IBLOCK_FIELD_".$propertyID)), GetMessage($parnterPrefix."IBLOCK_ADD_ERROR_REQUIRED"));
		}
	}

	if ($arParams["USE_CAPTCHA"] == "Y") {
		if (!$APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"])) {
			$arResult["ERRORS"][] = GetMessage($parnterPrefix."IBLOCK_FORM_WRONG_CAPTCHA");
		}
	}		

	if (empty($arResult["ERRORS"])) {
		$arUpdateValues["NAME"] = "element ".date("d.m.Y H:i:s");
		$arUpdateValues["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
		$arUpdateValues["MODIFIED_BY"] = $USER->GetID();
		$arUpdateValues["PROPERTY_VALUES"] = $arUpdatePropertyValues;
		if (strlen($arUpdateValues["DATE_ACTIVE_FROM"]) <= 0) {
			$arUpdateValues["DATE_ACTIVE_FROM"] = ConvertTimeStamp(time() + CTimeZone::GetOffset(), "FULL");
		}
		$arUpdateValues["ACTIVE"] = ($arParams["ACTIVE"] == "Y") ? "Y" : "N";
		//Add new element
		$oElement = new CIBlockElement();
		if (!$arParams["ID"] = $oElement->Add($arUpdateValues, false, true, false)) {
			$arResult["ERRORS"][] = $oElement->LAST_ERROR;
		} else {
			if(strlen($arParams["NAME_POST_EVENT"]) > 0) {
				$arEventFields = array();
				$arFileMail = array();
				foreach ($arUpdateValues["PROPERTY_VALUES"] as $key => $value) {
					$codeFields = $arResult["PROPERTY_LIST_FULL"][$key]["CODE"];
					if ($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"] == "F") {
						$dbFile = CIBlockElement::GetList(
							Array(), 
							array("IBLOCK_ID" =>  $arParams["IBLOCK_ID"], "ID" => $arParams["ID"]), 
							false, 
							false, 
							array("IBLOCK_ID", "ID", "PROPERTY_".$codeFields)
						);
						while($arTemp = $dbFile->GetNext())
							$arFileMail[] = $arTemp["PROPERTY_".$codeFields."_VALUE"];
					} elseif(in_array($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"], array("S", "N"))) {
						if(!is_array($value))
							$arEventFields["PROPERTY_".$codeFields] = $value;
						else
							$arEventFields["PROPERTY_".$codeFields] = $value["VALUE"]["TEXT"];
					} elseif(in_array($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"], array("L"))) {
						$arEventFields["PROPERTY_".$codeFields] = print_r($arResult["PROPERTY_LIST_FULL"][$key]["ENUM"][$value]["VALUE"], true);
					}
				}
				$arEventFields["ID"] = $arParams["ID"];
				CEvent::Send($arParams["NAME_POST_EVENT"], SITE_ID, $arEventFields, "Y", "", $arFileMail);
			}

			if (!empty($arParams["RETURN_URL"])) {
				LocalRedirect($arParams["RETURN_URL"]);

			} else {
				$sRedirectUrl = $APPLICATION->GetCurPageParam(
					"czebra_form_success=Y&strIMessage=".urlencode($arParams["USER_MESSAGE_ADD"]), 
					array("czebra_form_success", "strIMessage"), 
					false
				);
				LocalRedirect($sRedirectUrl);
			}
			exit();
		}
	}
}
if (!empty($arResult["ERRORS"])) {
	foreach ($arUpdateValues as $key => $value) {
		if ($key == "IBLOCK_SECTION") {
			$arResult["ELEMENT"][$key] = array();
			if (!is_array($value)) {
				$arResult["ELEMENT"][$key][] = array("VALUE" => htmlspecialcharsbx($value));
			} else {
				foreach ($value as $vkey => $vvalue) {
					$arResult["ELEMENT"][$key][$vkey] = array("VALUE" => htmlspecialcharsbx($vvalue));
				}
			}
		} elseif ($key == "PROPERTY_VALUES") {
			//Skip
		} elseif ($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"] == "F") {
			//Skip
		} elseif ($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"] == "HTML") {
			$arResult["ELEMENT"][$key] = $value;
		} else {
			$arResult["ELEMENT"][$key] = htmlspecialcharsbx($value);
		}
	}

	foreach ($arUpdatePropertyValues as $key => $value) {
		if ($arResult["PROPERTY_LIST_FULL"][$key]["PROPERTY_TYPE"] != "F") {
			$arResult["ELEMENT_PROPERTIES"][$key] = array();
			if (!is_array($value)) {
				$value = array(
					array("VALUE" => $value),
				);
			}
			foreach ($value as $vv) {
				if (is_array($vv)) {
					if (array_key_exists("VALUE", $vv))
						$arResult["ELEMENT_PROPERTIES"][$key][] = array(
							"~VALUE" => $vv["VALUE"],
							"VALUE" => !is_array($vv["VALUE"])? htmlspecialcharsbx($vv["VALUE"]): $vv["VALUE"],
						);
					else
						$arResult["ELEMENT_PROPERTIES"][$key][] = array(
							"~VALUE" => $vv,
							"VALUE" => $vv,
						);
				} else {
					$arResult["ELEMENT_PROPERTIES"][$key][] = array(
						"~VALUE" => $vv,
						"VALUE" => htmlspecialcharsbx($vv),
					);
				}
			}
		}
	}
}

// prepare captcha
if ($arParams["USE_CAPTCHA"] == "Y") {
	$arResult["CAPTCHA_CODE"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
}

$arResult["MESSAGE"] = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST["strIMessage"]) && is_string($_REQUEST["strIMessage"]))
	$arResult["MESSAGE"] = htmlspecialcharsbx($_REQUEST["strIMessage"]);

$this->includeComponentTemplate();
