<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="wrap_news">
    <?require(realpath(dirname(__FILE__)).'/ajax.php')?>
</div>
<?if($arParams["HIDE_NAV_STRING"] != "Y"):?>
    <?=$arResult["NAV_STRING"];?>
    <input type="hidden" id="ajaxParams" value="<?=\Czebra\AjaxLoading::getCryptArray($component->GetName(), $templateName, $arParams)?>" />
<?endif?>