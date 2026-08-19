<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-item-workarea"><div class="row">
    <?require(realpath(dirname(__FILE__)).'/ajax.php')?>
    <div class="more-items-workarea col-lg-4 col-md-4 col-sm-12 col-xs-12" style="display: none;">
        <a href="" class="more-items-text"><img src="<?=SITE_TEMPLATE_PATH?>/front/images/moreitems.png">Показать еще<br>14 товаров</a>
    </div>
</div></div>
<?if($arParams["HIDE_NAV_STRING"] != "Y"):
    GLOBAL ${$arParams["FILTER_NAME"]};
?>
    <div id="wrap-pager">
        <?=$arResult["NAV_STRING"];?>
    </div>
    <input type="hidden" id="ajaxParams" value="<?=\Czebra\BFS\AjaxLoading::getCryptArray($component->GetName(), $templateName, $arParams)?>" />

    <input type="hidden" id="ajaxFilter" value="<?=urlencode(base64_encode(gzcompress(json_encode(${$arParams["FILTER_NAME"]}))));?>" />
    <input type="hidden" id="ajaxCallback" value="afterInstertAjax()" />
    <input type="hidden" id="ajaxContener" value=".catalog-item-workarea .row" />
<?endif?>