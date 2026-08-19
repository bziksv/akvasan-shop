<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?if ($arResult["NavPageCount"] > 1) :?>
    <a id="btnAjaxLoading">Показать ещё</a>

    <input type="hidden" id="ajaxCountPages" value="<?=$arResult["NavPageCount"]?>" />
    <input type="hidden" id="ajaxNumberPage" value="<?=$arResult["NavPageNomer"]?>" />

    <input type="hidden" id="ajaxCallback" value="callBack();" />
    <input type="hidden" id="ajaxFilter" value="" />
    <input type="hidden" id="ajaxContener" value="#wrap_news" />
<?endif?>