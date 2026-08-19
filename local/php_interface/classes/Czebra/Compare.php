<?php
namespace Czebra; 

class Compare
{
    private const IBLOCK = 5;

    public static function Add($ID)
    {
        $IBlock = self::IBLOCK;
        if (!in_array($ID, $_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"] ?? [])) {
            $_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"][intval($ID)] = array('ID' => $ID);

            $result = array(
                "STATUS" => "ОК",
                "ID" => $ID,
                "COUNT" => count($_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"]),
            );
        } else {
            $result = array(
                "STATUS" => "ERROR",
                "ERROR_TEXT" => "Element in list compare",
            );
        }
        return json_encode($result);
    }

    public static function Delete($ID)
    {
        $IBlock = self::IBLOCK;
        if (array_key_exists($ID, $_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"])) {
            //unset($_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"][array_search($ID, $_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"])]);
            unset($_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"][$ID]);

            $result = array(
                "STATUS" => "ОК",
                "ID" => $ID,
                "COUNT" => count($_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"]),
            );
        } else {
            $result = array(
                "STATUS" => "ERROR",
                "ERROR_TEXT" => "Element in list compare",
            );
        }
        return json_encode($result);
    }

    public static function List()
    {
        $IBlock = self::IBLOCK;
        $arCompare = array();
        foreach($_SESSION["CATALOG_COMPARE_LIST"][$IBlock]["ITEMS"] as $key=>$value){
            $arCompare[] = $key;
        }
        return json_encode($arCompare);
    }
}
