<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$frame = $this->createFrame()->begin('<a href="#" class="basket is-relative"><div class="is-basket-number"></div></a>');
?>
<a href="<?=$arParams["PATH_TO_ORDER"]?>" class="basket is-relative"><div class="is-basket-number"><?=$arResult['NUM_PRODUCTS'] ?></div></a>
<input id="basket_settings" type="hidden" value="<?=\Czebra\AjaxLoading::getCryptArray($component->GetName(), $templateName, $arParams);?>"/>