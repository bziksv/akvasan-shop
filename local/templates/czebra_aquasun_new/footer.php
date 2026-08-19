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
            <div class="footer-main">
                <div class="footer-brand">
                    <img src="<?=SITE_TEMPLATE_PATH?>/front/img/logo-footer.png" alt="АкваСан" class="footer-brand__logo">
                    <p class="footer-brand__copy">© <?=date("Y")?> Все права защищены</p>
                    <div class="footer-hours visible-xs visible-sm">
                        <p>Пн-Пт <span>09:00 - 18:00</span></p>
                        <p>Сб-Вс <span>09:00 - 17:00</span></p>
                    </div>
                </div>

                <div class="footer-address">
                    <p class="footer-address__line">г. Воронеж,</p>
                    <p class="footer-address__line">ул. Холмистая 1г,</p>
                    <p class="footer-address__line">павильон 113</p>
                    <div class="footer-social visible-xs visible-sm">
                        <a href="https://vk.com/aquasanvoronezh" class="footer-social__link" aria-label="ВКонтакте"><span class="vk-footer"></span></a>
                        <a href="https://www.instagram.com/akvasanshop/" class="footer-social__link" aria-label="Instagram"><span class="insta-footer"></span></a>
                    </div>
                </div>

                <div class="footer-contacts">
                    <a href="tel:+74732299621" class="footer-contacts__phone">+7 (473) 229-96-21</a>
                    <a href="" id="call-order-footer" class="footer-contacts__callback call-back-footer">Заказать звонок</a>
                    <div class="footer-social hidden-xs hidden-sm">
                        <a href="https://vk.com/aquasanvoronezh" class="footer-social__link" aria-label="ВКонтакте"><span class="vk-footer"></span></a>
                    </div>
                </div>

                <div class="footer-nav">
                    <div class="footer-nav__top">
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
                </div>
            </div>

            <div class="footer-catalog">
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

            <div class="footer-secondary">
                <div class="documents-menu">
                    <ul>
                        <li><a href="/country/">Страны товаров</a></li>
                        <li><a href="/articles/">Статьи</a></li>
                        <li><a href="/contacts/#reviews">Отзывы</a></li>
                        <li><a href="/vozvrat/vozvrat.php">Возврат</a></li>
                    </ul>
                </div>

                <div class="footer-partner">
                    <a href="https://prime-ltd.su/?from=https://akvasan-shop.ru/" rel="nofollow" target="_blank">
                        <img src="https://prime-ltd.su/logo/white.svg" alt="Prime — продвижение сайтов" class="footer-partner__logo">
                    </a>
                </div>
            </div>

            <div class="footer-legal">
                Для обеспечения корректной работы сайта мы используем файлы
                <a href="/politika-cookies-akvasan.pdf" target="_blank">cookie</a>
                и
                <a href="/rules-recommendation-akvasan.pdf" target="_blank">рекомендательные технологии</a>.
                Сбор информации необходим для персонализации контента, анализа посещаемости и оптимизации функционала.
                Продолжая пользоваться сайтом, вы даёте согласие на обработку персональных данных в соответствии с
                <a href="/personal-data-akvasan.pdf" target="_blank">Политикой обработки персональных данных</a>.
            </div>
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
