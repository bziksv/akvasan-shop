<?php
namespace Czebra\BFS;

use Bitrix\Main\Loader;
use Bitrix\Sale\Fuser;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket as BitrixBasket;

class Basket
{
    public static function Add($param)
    {
        if (Loader::IncludeModule("sale") && Loader::IncludeModule("catalog")) {

            if (isset($param["PRODUCT_ID"])
                && !empty($param["PRODUCT_ID"])
                && ctype_digit($param["PRODUCT_ID"])
            ) {
                $productID = $param["PRODUCT_ID"];

                $quanity = 1;
                if (isset($param["QUANITY"])
                    && !empty($param["QUANITY"])
                    && is_numeric($param["QUANITY"])
                ) {
                    $quanity = $param["QUANITY"];
                }

                $ID = \Add2BasketByProductID($productID, $quanity);

                $result = array(
                    "STATUS" => "ОК",
                    "ID" => $ID,
                );
            } else {
                $result = array(
                    "STATUS" => "ERROR",
                    "ERROR_TEXT" => "Czebra\BFS\Basket\add - Problem with ID",
                );
            }
        } else {
            $result = array(
                "STATUS" => "ERROR",
                "ERROR_TEXT" => "Czebra\BFS\Basket\add - no modules 'sale' and 'catalog'",
            );
        }

        return json_encode($result);
    }

    public static function getList()
    {
        if (Loader::includeModule("sale")) {
            $result = array("STATUS" => "ОК");

            $dbBasketItems = \CSaleBasket::GetList(
                array(),
                array(
                    "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
                    "LID" => SITE_ID,
                    "ORDER_ID" => "NULL"
                ),
                false,
                false,
                array("ID", "PRODUCT_ID", "DELAY")
            );
            while ($arItems = $dbBasketItems->Fetch()) {
                $result["basket"][] = $arItems["PRODUCT_ID"];
                if ($arItems["DELAY"] == "Y") {
                    $result["delay"][] = $arItems["PRODUCT_ID"];
                }
            }

            //$arCompare = array();
            //foreach($_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"] as $key=>$value){
            //    $arCompare[] = $key;
            //}


        } else {
            $result = array(
                "STATUS" => "ERROR",
                "ERROR_TEXT" => "Czebra\BFS\Basket\add - no modules 'sale'",
            );
        }

        return json_encode($result);
    }

    public static function Delete($ID)
    {
        if (Loader::includeModule("sale")) {
            $basket = BitrixBasket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
            $basket->getItemById($ID)->delete();
            $basket->save();

            $info["SUM_ORDER"] = $basket->getPrice();
            $info["SUM_ORDER_FULL"] = $basket->getBasePrice(); // Сумма без учета скидок

            return json_encode($info);
        }
        return false;
    }

    public static function Update($param)
    {
        if (Loader::includeModule("sale")) {
            $basket = BitrixBasket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
            $basket->getItemById($param["ID"])->setField('QUANTITY', $param["QUANTITY"]);
            $basket->save();
			
			$discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
			$discounts->calculate();
			$result = $discounts->getApplyResult(true);
			$showPrices = $discounts->getShowPrices();
			
			$arQuantity = $basket->getQuantityList();

            $info = array();
            $basketTemp = array();
			$sum = 0;
            foreach ($result['PRICES']['BASKET'] as $id => $basketItem) {
                $basketTemp[] = array(
                    'ID' => $id,
                    'PRICE' => $basketItem['PRICE'],
                    'SUM' => round($basketItem['PRICE']*$arQuantity[$id])
                );
				
				$sum += round($basketItem['PRICE']*$arQuantity[$id]);
            }
			
            $info["ITEM"] = $basketTemp;
            $info["SUM_ORDER"] = $sum;
            $info["SUM_ORDER_FULL"] = $sum;
            
            return json_encode($info);
        }
        return false;
    }
}
