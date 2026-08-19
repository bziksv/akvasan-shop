<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


foreach ($arResult["PROPERTIES"] as $key => $prop) {

	if(in_array($prop['CODE'], ['SITE_NAME']))
		continue;

    if (is_string($prop["VALUE"]) && strlen($prop["VALUE"]) > 0 && !in_array($key, \Czebra\Base\Consts::STOP_PROP_ELEMENT)) {
        $arResult["FULL_PROP"][$key] = $prop;
        if ($prop['NAME']=='Бренд') {
            $file = "/upload/brands/" . Cutil::translit($prop["VALUE"],"ru",array()) . ".png";
            if (file_exists($_SERVER["DOCUMENT_ROOT"].$file)) {
                $arResult["FULL_PROP"][$key]["IMG"] = $file;
            }
        }
    }
}

if($arResult["ITEM_PRICES"][$arResult["ITEM_PRICE_SELECTED"]]["PRICE"] < 10)
	$APPLICATION->AddHeadString('<meta name="robots" content="noindex, nofollow" />', true);





if ($USER->IsAdmin()){

	$rsStore = \Bitrix\Catalog\StoreTable::getList(array(
		'select' => array('TITLE', 'ID'),
		'filter' => array('ACTIVE'>='Y'),
	));
	
	while($arStore=$rsStore->fetch()){
	
		$arFilter = Array("PRODUCT_ID"=>array($arResult["ID"]),"STORE_ID"=>$arStore['ID'] );
		$rsStoreAmount = CCatalogStoreProduct::GetList(Array(),$arFilter,false,false,Array());
		while($arStoreAmount = $rsStoreAmount->Fetch())
		{
			$arStore['AMOUNT'] = $arStoreAmount["AMOUNT"];
		}
	
	
		$arResult['STORES'][$arStore['ID']]=$arStore;
	
	
	}


}
