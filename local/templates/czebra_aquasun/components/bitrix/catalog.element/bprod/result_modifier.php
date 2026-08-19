<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


foreach ($arResult["PROPERTIES"] as $key => $prop) {
    if (strlen($prop["VALUE"]) > 0 && !in_array($key, \Czebra\Base\Consts::STOP_PROP_ELEMENT)) {
        $arResult["FULL_PROP"][$key] = $prop;
    }
}
