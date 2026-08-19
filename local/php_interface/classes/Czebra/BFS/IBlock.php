<?php
namespace Czebra\BFS;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;

class IBlock
{
    public function GetID($code)
    {
        if (!Loader::includeModule("iblock")) {
            return false;
        }
        $dbIblock = IblockTable::getList(array(
            "filter" => array("CODE" => $code),
            "select" => array("ID"),
        ));
        if ($arIblock = $dbIblock->fetch()) {
            return $arIblock["ID"];
        }
        return false;
    }

    public function SaveWithoutCode(&$arFields)
    {
        if (strlen($arFields["CODE"]) == 0) {
            if ($arFields["IBLOCK_TYPE_ID"] == "1c_catalog") { //Для инфоблоков приходящих из 1с
                $i = 1;
                while (strlen($arFields["CODE"]) == 0) {
                    $tempCode = "CATALOG_1C_".$i;
                    $dbIblock = CIBlock::GetList(Array(), Array("CODE" => $tempCode), false);
                    if (!($arIblock = $dbIblock->Fetch()))
                        $arFields["CODE"] = $tempCode;
                    $i++;
                }
            } else {
                global $APPLICATION;
                $APPLICATION->throwException("Введите символьный код.");
                return false;
            }
        } elseif(!preg_match('|^[A-Z0-9_]+$|i', $arFields["CODE"])) {
            global $APPLICATION;
            $APPLICATION->throwException("Cимвольный код должен состоять из латинских букв, цифр или символа нижнего подчеркивания.");
            return false;
        } elseif(!preg_match('|^[A-Z]+$|i', $arFields["CODE"][0])) {
            global $APPLICATION;
            $APPLICATION->throwException("Cимвольный код должен начинаться с латинской буквы.");
            return false;
        } else {
            $dbIblock = CIBlock::GetList(Array(), Array("CODE" => $arFields["CODE"], "!ID" => $arFields["ID"]), false);
            if ($arIblock = $dbIblock->Fetch()) {
                global $APPLICATION;
                $APPLICATION->throwException("Инфоблок с таким символьным кодом уже существует.");
                return false;
            }
        }
        $arFields["CODE"] = strtoupper($arFields["CODE"]);
    }
}
