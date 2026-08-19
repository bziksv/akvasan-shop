<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$curPage = $APPLICATION->GetCurPage(true);?>

</div></main>
<footer class="footer"><div class="container">
    <div class="copyright col-lg-4 col-md-4 col-xs-12"><span>© 2017</span><img src="<?=SITE_TEMPLATE_PATH?>/front/images/logofooter.png"></div>
    <div class="footer-right col-lg-8 col-md-8 col-xs-12">
        <div class="menu-footer">
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "simple",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "dop",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(""),
                    "MENU_CACHE_TIME" => "360000",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "bottom",
                    "USE_EXT" => "N"
                )
            );?>
        </div>
        <div class="contacts-footer">
            <div class="phone"></div>
            <a href="tel:+74732299621" class="footer-phone">+7 (473) 229-96-21</a>
        </div>
        <div class="address">
            <div class="pin"></div>
            <span>г. Воронеж, ул.Холмистая 1г. павильон №113</span>
        </div>
    </div>
</div></footer>
<?if($_REQUEST["formresult"] == "addok"):?>
    <input type="hidden" id="czebra_form_success" value="show" />
<?endif?>
</div></body></html>