<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if ($arResult["ITEMS"]) {
    if ($arParams['AJAX'] == 'Y') {
        require(realpath(dirname(__FILE__)).'/ajax.php');
    } else {
        require(realpath(dirname(__FILE__)).'/basic.php');
    }
}