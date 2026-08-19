<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="container-catalog">
    <div class="row">
        <?require(realpath(dirname(__FILE__)).'/ajax.php')?>
        <div class="container-product col-lg-4 col-md-4" style="display: none;">
            <a href="" class="button-more-product"><span>показать еще <?=$arParams["PAGE_ELEMENT_COUNT"]?> товаров <div class="more-product"></div></span></a>
        </div>
    </div>
</div>
<?if($arParams["HIDE_NAV_STRING"] != "Y"):
    GLOBAL ${$arParams["FILTER_NAME"]};
?>
    <div id="wrap-pager">
        <?=$arResult["NAV_STRING"];?>
    </div>

	<div class="row">
		<?
        if(($arResult["DEPTH_LEVEL"] == 1 && count($arrFilter) > 2) || ($arResult["DEPTH_LEVEL"] > 1 && count($arrFilter) > 1))
            $APPLICATION->ShowViewContent('sotbit_seometa_bottom_desc');
        else
            echo $arResult['DESCRIPTION'];
		?>
	</div>
    <input type="hidden" id="ajaxParams" value="<?=\Czebra\BFS\AjaxLoading::getCryptArray($component->GetName(), $templateName, $arParams)?>" />

    <input type="hidden" id="ajaxFilter" value="<?=urlencode(base64_encode(gzcompress(json_encode(${$arParams["FILTER_NAME"]}))));?>" />
    <input type="hidden" id="ajaxCallback" value="afterInstertAjax()" />
    <input type="hidden" id="ajaxContener" value=".catalog-item-workarea .row" />
<?endif?>
