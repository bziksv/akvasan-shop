<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$curPage = $APPLICATION->GetCurPage(true);?><?/*
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

</div></body></html>

*/?>

</div></div></main>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="logo-footer col-lg-3 col-md-3 col-xs-6">
                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/logo-footer.png" alt="Логотип" class="img-responsive">
                    <span>© <?=date("Y")?> Все права зашищены</span>
                </div>
                <div class="working-time-footer hidden-lg hidden-md col-xs-6">
                    <div class="working-time">
                        <p>Пн-Пт <span>09:00 - 18:00</span></p>
                        <p>Сб-Вс <span>09:00 - 17:00</span></p>
                    </div>
                </div>
                <div class="phone-footer col-lg-2 col-lg-push-2 col-md-2 col-md-push-2">
                    <a href="tel:+74732299621">+7 (473) 229-96-21</a>
                    <a href="" id="call-order-footer" class="call-back-footer">Заказать звонок</a>
                    <div class="social-footer hidden-sm hidden-xs">
                        <a href="https://vk.com/aquasanvoronezh"><div class="vk-footer"></div></a>
                        <?/*<a href="#"><div class="ok-footer"></div></a>*/?>
                    </div>

                    <div class='czebra'><a href="http://www.czebra.ru" target="_blank"><img src="/local/templates/czebra_aquasun_new/front/img/logo-cz.png" alt="Цветная зебра"></a></div>


					<!--<script type="text/javascript" src="https://incut.prime-ltd.su/incut/incut.js"></script>
					<link rel="stylesheet" href="https://incut.prime-ltd.su/incut/incut.css">
					<a class="prime-incut white colour"></a>-->
					<div style="width: 70%;"><a href="https://prime-ltd.su/" rel="nofollow" target="_blank"><img src="http://prime-ltd.su/logo/white.svg"></a></div>
                </div>
                <div class="address-footer col-lg-2 col-lg-pull-2 col-md-2 col-md-pull-2">
                    <span>г. Воронеж,</span>
                    <span>ул. Холмистая 1г,</span>
                    <span>павильон 113</span>

                    <div class="social-footer hidden-lg hidden-md">
                        <a href="https://vk.com/aquasanvoronezh"><div class="vk-footer"></div></a>
                        <?/*<a href="#"><div class="ok-footer"></div></a>*/?>
                        <a href="https://www.instagram.com/akvasanshop/"><div class="insta-footer"></div></a>
                    </div>

                </div>
                <div class="footer-menu col-lg-5 col-md-5">
                    <div class="footer-menu-top">
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
                                "ROOT_MENU_TYPE" => "top",
                                "USE_EXT" => "N"
                            )
                        );?>
                    </div>
                    <div class="footer-menu-bottom">
                        <div class="row">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "catalog_footer",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "dop",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(""),
                                    "MENU_CACHE_TIME" => "360000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "catalog_footer",
                                    "USE_EXT" => "Y"
                                )
                             );?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="documents-menu">
                <ul>
                    <li><a href="/country/">Страны товаров</a></li>
                    <!--<li><a href="/offer/">Оферта</a></li>
                    <li><a href="/personal-data-akvasan.pdf">Политика конфиденциальности</a></li>
                    <li><a href="/politika-cookies-akvasan.pdf">Политика использования cookie</a></li>
                    <li><a href="/rules-recommendation-akvasan.pdf">Правила применения рекомендательных технологий</a></li>-->
                    <li><a href="/articles/">Статьи</a></li>
                    <li><a href="/contacts/#reviews">Отзывы</a></li>
					<li><a href="/vozvrat/vozvrat.php">Возврат</a></li>
                </ul>
            </div>

			<div style="color: #fff; border-top: #fff 1px solid; padding-top: 15px; margin-top: 20px;">Для обеспечения корректной работы сайта мы используем файлы <a style="color: #fff;" target="_blank" href="/politika-cookies-akvasan.pdf">cookie</a> и <a style="color: #fff;" target="_blank" href="/rules-recommendation-akvasan.pdf">рекомендательные технологии</a>. Сбор информации необходим для персонализации контента, анализа посещаемости и оптимизации функционала. Продолжая пользоваться сайтом, вы даёте согласие на обработку персональных данных в соответствии с <a style="color: #fff;" target="_blank" href="/personal-data-akvasan.pdf">Политикой обработки персональных данных</a>.</div>

        </div>
    </footer>
    <?if($_REQUEST["formresult"] == "addok"):?>
    <input type="hidden" id="czebra_form_success" value="show" />
<?endif?>

</div>

    <? //$APPLICATION->ShowCSS();?>
    <? //$APPLICATION->ShowHeadScripts();?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "multilevel-mobile",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "CHILD_MENU_TYPE" => "dop",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "360000",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "ROOT_MENU_TYPE" => "catalog",
            "USE_EXT" => "Y"
        )
    );?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js', 'ym');

    ym(51035207, 'init', {webvisor:true, clickmap:true, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/51035207" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->



</body>
</html>
