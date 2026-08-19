<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use	Bitrix\Iblock;

class CCzebraSimpleDataComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }

        $arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
        $arParams["IBLOCK_ID"] = intval(trim($arParams["IBLOCK_ID"]));
        
        if (!is_array($arParams["FIELD_CODE"])) {
            $arParams["FIELD_CODE"] = array();
        }
        foreach ($arParams["FIELD_CODE"] as $key=>$val) {
            if (!$val) {
                unset($arParams["FIELD_CODE"][$key]);
            }
        }
        if (!is_array($arParams["PROPERTY_CODE"])) {
            $arParams["PROPERTY_CODE"] = array();
        }
        foreach ($arParams["PROPERTY_CODE"] as $key=>$val) {
            if ($val === "") {
                unset($arParams["PROPERTY_CODE"][$key]);
            }
        }
        $arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
        if (strlen($arParams["SORT_BY1"]) <= 0) {
            $arParams["SORT_BY1"] = "SORT";
        }
        if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"])) {
            $arParams["SORT_ORDER1"] = "DESC";
        }

        if (strlen($arParams["SORT_BY2"]) <= 0) {
            $arParams["SORT_BY2"] = "ID";
        }
        if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"])) {
            $arParams["SORT_ORDER2"] = "ASC";
        }

        if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
        {
            $arParams["FILTER_NAME"] = "";
        }
        else
        {
            $arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
            if (!is_array($arrFilter)) {
                $arParams["FILTER_NAME"] = "";
            }
        }

        if (!isset($arParams["COUNT"]) || strlen($arParams["COUNT"]) == 0) {
            $arParams["COUNT"] = 36000000;
        } else {
            $arParams["COUNT"] = intval($arParams["COUNT"]);
        }
        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->arResult["ITEMS"] = $this->getItems($this->arParams);
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }

    protected function getItems($arParams)
    {
        $arItems = array();
        if($arParams["IBLOCK_ID"] > 0)
        {
            CModule::IncludeModule("iblock");
            $dbElements = \CIBlockElement::GetList(
                $this->getSortForGetList($arParams["SORT_BY1"], $arParams["SORT_BY2"], $arParams["SORT_ORDER1"], $arParams["SORT_ORDER2"]),
                $this->getFilterForGetList($arParams["IBLOCK_ID"], $arParams["FILTER_NAME"]),
                $this->getGroupByForGetList($arParams), 
                $this->getNavForGetList($arParams["COUNT"]),
                $this->getSelectForGetList($arParams["FIELD_CODE"], $arParams["PROPERTY_CODE"])
            );
            while ($arElement = $dbElements->GetNextElement()) {
                $arFields = $this->parseFields($arElement->GetFields());
                if ($arParams["SHOW_ALL_PROPERTIES"] == "Y") {
                    $arFields["PROPERTIES"] = $arElement->GetProperties();
                }
                $arItems[] = $arFields;
            }
        }
        return $arItems;
    }

    protected function getSortForGetList($by1, $by2, $order1, $order2)
    {
        $arSort = array();
        $arSort[$by1] = $order1;
        $arSort[$by2] = $order2;
        return $arSort;
    }

    protected function getSelectForGetList($fields, $props)
    {
        if (is_array($fields) && is_array($props) && count($fields) == 0 && count($props) == 0) {
            $arSelect = array("*");       
        } else {
            $arSelect = array("ID","IBLOCK_ID");
            foreach ($fields as $field) {
                $arSelect[] = $field;
            }
            foreach ($props as $prop) {
                $arSelect[] = "PROPERTY_" . $prop;
            }
        }
        return $arSelect;
    }

    protected function getFilterForGetList($iblockID, $filterName)
    {
        $arFilter = Array("IBLOCK_ID" => $iblockID, "ACTIVE" => "Y");
        if (strlen($filterName) > 0) {
            GLOBAL ${$filterName};
            if (is_array(${$filterName})) {
                $arFilter = array_merge($arFilter, ${$filterName});
            }
        }
        return $arFilter;
    }

    protected function getNavForGetList($count)
    {
        return array("nPageSize" => $count);
    }

    protected function getGroupByForGetList($arParams)
    {
        return false;
    }

    protected function parseFields($arFields)
    {
        $arProps = array();
        $arTempFields = array();
        foreach ($arFields as $key => $val) {
            $tempKey = str_replace("PROPERTY_", "", $key);
            if ($tempKey != $key) {
                if (stristr($tempKey, "VALUE_ID") === false && $tempKey[0] !== "~") {
                    $tempKey = str_replace("_VALUE", "", $tempKey);
                    $arProps[$tempKey]["VALUE"] = $val;
                }
            } else {
                $arTempFields[$key] = $arFields[$key];
            }
        }
        Iblock\Component\Tools::getFieldImageData(
			$arTempFields,
			array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
			Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
			'IPROPERTY_VALUES'
		);
        $arTempFields["DISPLAY_PROPERTIES"] = $arProps;
        return $arTempFields;
    }
}
