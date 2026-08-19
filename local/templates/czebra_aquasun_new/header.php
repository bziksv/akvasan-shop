<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CMain $APPLICATION */
global $APPLICATION;
$curPage = $APPLICATION->GetCurPage(true);?>
<!doctype html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $APPLICATION->ShowHead();

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/bfs/css/czebra.valideted.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/bootstrap.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/jquery.mCustomScrollbar.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/style.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/jquery.jscrollpane.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/jquery.fancybox.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/cz_style.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/js/mobile-menu/slide-menu.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/js/mobile-menu/slide-menu-theme.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/custom.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/fonts/fontawesome/css/all.css");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery-3.2.1.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/bootstrap.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.bxslider.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery-ui.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.bootstrap-touchspin.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.jscrollpane.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.mousewheel.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/mwheelIntent.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/mobile-menu/slide-menu.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/init.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.fancybox.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.bootstrap-touchspin.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.mCustomScrollbar.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/readmore.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.dotdotdot.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/main.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/jquery.maskedinput.min.js"); //только с подключенным jquery
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.valideted.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.valideted.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.loading.js"); //только с подключенным jquery
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.fancybox.min.js");
    $APPLICATION->AddHeadString('<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" rel="stylesheet" type="text/css"  />');
    ?>
    <title><?php $APPLICATION->ShowTitle()?></title>
</head>
<body>

<div class="page">

    <div id="panel">
        <?php $APPLICATION->ShowPanel();?>
    </div>

    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="header-address col-lg-4 col-md-4">
                        <span>г. Воронеж, ул. Холмистая 1г, павильон 113</span>
                    </div>
                    <div class="header-social col-lg-4 col-md-4">
                        <span>Мы в соцсетях:</span>
                        <a href="https://vk.com/aquasanvoronezh"><div class="vk-header"></div></a>
                    </div>
                    <div class="top-menu col-lg-4 col-md-4">
                        <?php $APPLICATION->IncludeComponent(
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
        </div>

        <div class="header-bottom">
            <div class="container">
                <div class="row">

                    <div class="mobile-icon-menu hidden-lg hidden-md">
                        <button></button>

                        <div class="drop-menu">
                            <?php $APPLICATION->IncludeComponent(
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
                    </div>

                    <div class="logo col-lg-3 col-md-3 col-xs-12">
                        <a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/front/img/logo.png" alt="Аквасан" class="img-responsive"></a>
                    </div>

                    <div class="wrap-title-header col-lg-3 col-md-3">
                        <?php if(CSite::InDir('/index.php')): ?>
                            <h1 class="title-header">Огромный выбор сантехники!</h1>
                        <?php else:?>
                            <div class="title-header">Огромный выбор сантехники!</div>
                        <?php endif;?>
                    </div>

                    <div class="phone-header col-lg-3 col-md-3">
                        <a href="tel:+74732299621" class="phone"> <span class="color-phone">+7 (473)</span> 229-96-21</a>
                        <a href="" id="call-order">Заказать звонок</a>
                    </div>
                    <div class="working-time col-lg-3 col-md-3">
                        <p>Пн-Пт <span>09:00 - 18:00</span></p>
                        <p>Сб-Вс <span>09:00 - 17:00</span></p>
                    </div>

                    <div class="wrapp-registration col-lg-2 col-md-2 hidden-sm hidden-xs">

                        <?php global $USER;?>
                        <?php if ($USER->IsAuthorized()):?>
                            <a href="/personal/" class="lk">Личный кабинет</a>
                        <?php else:?>
                            <a href="/login/?backurl=<?=urlencode($APPLICATION->GetCurPage(false))?>" class="entry-site">Вход</a>
                            <a href="/login/?register=yes" class="reg-site">Регистрация</a>
                        <?php endif?>
                    </div>

                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "bprod",
                        Array(
                            "HIDE_ON_BASKET_PAGES" => "N",
                            "PATH_TO_AUTHORIZE" => "",
                            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                            "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                            "PATH_TO_PROFILE" => SITE_DIR."personal/",
                            "PATH_TO_REGISTER" => SITE_DIR."login/",
                            "POSITION_FIXED" => "N",
                            "SHOW_AUTHOR" => "N",
                            "SHOW_EMPTY_VALUES" => "Y",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_PERSONAL_LINK" => "N",
                            "SHOW_PRODUCTS" => "N",
                            "SHOW_TOTAL_PRICE" => "N",
                            "COMPOSITE_FRAME_MODE" => "N"
                        )
                    );?>

                </div>
            </div>
        </div>
    </header>

    <main class="main">

        <div class="sticky-menu hidden-sm hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="menu-catalog-sticky col-lg-2 col-md-2">
                        <span>Каталог товаров</span>

                        <div class="container-menu-sticky">
                            <?php $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "multilevel",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "dop",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "2",
                                    "MENU_CACHE_GET_VARS" => array(
                                    ),
                                    "MENU_CACHE_TIME" => "360000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "ROOT_MENU_TYPE" => "catalog",
                                    "USE_EXT" => "Y",
                                    "COMPONENT_TEMPLATE" => "multilevel",
                                    "COMPOSITE_FRAME_MODE" => "A",
                                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                                ),
                                false
                            );?>
                        </div>

                    </div>
                    <div class="phone-sticky col-lg-3 col-md-3">
                        <a href="tel:+74732299621">+7 (473) 229-96-21</a>
                    </div>
                    <div class="search-sticky col-lg-3 col-md-3">
                        <form action="/catalog/">
                            <input name="q" type="text" placeholder="Введите запрос">
                            <input type="hidden" name="spell" value="1">
                            <button></button>
                        </form>
                    </div>
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "sticky",
                        Array(
                            "HIDE_ON_BASKET_PAGES" => "N",
                            "PATH_TO_AUTHORIZE" => "",
                            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                            "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                            "PATH_TO_PROFILE" => SITE_DIR."personal/",
                            "PATH_TO_REGISTER" => SITE_DIR."login/",
                            "POSITION_FIXED" => "N",
                            "SHOW_AUTHOR" => "N",
                            "SHOW_EMPTY_VALUES" => "Y",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_PERSONAL_LINK" => "N",
                            "SHOW_PRODUCTS" => "N",
                            "SHOW_TOTAL_PRICE" => "N",
                            "COMPOSITE_FRAME_MODE" => "N"
                        )
                    );?>

                    <div class="comparison-sticky col-lg-2 col-md-2">
                        <a href="/catalog/compare.php" class="comparison">В сравнении (<span class="counter-comparison">0</span>)</a>
                    </div>
                    <div class="button-sticky col-lg-3 col-md-3">
                        <a href="/personal/cart/">Заказать</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">

            <div class="row">

                <div class="search-mobile hidden-lg hidden-md">
                    <div class="search-container">
                        <form action="/catalog/">
                            <input name="q" type="text" placeholder="Введите запрос">
                            <input type="hidden" name="spell" value="1">
                            <button>
                                <span class="mobile-search"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="left-container col-lg-4  col-md-4  col-xs-12">
                    <div class="menu">

                        <?php if(($curPage == "/index.php")
                        || (CSite::InDir('/catalog/') && !CSite::InDir('/catalog/product/') && !CSite::InDir('/catalog/compare.php'))
                        ):?>
                        <div class="title-menu">
                            <?php else :?>
                            <div class="title-menu title-menu-border active-radius">
                                <?php endif?>

                                <span class="hidden-xs hidden-sm">Каталог товаров</span>
                                <span class="hidden-lg hidden-md button-catalog-menu">Каталог сантехники</span>
                            </div>

                            <?php if(($curPage == "/index.php")
                            || (CSite::InDir('/catalog/') && !CSite::InDir('/catalog/product/') && !CSite::InDir('/catalog/compare.php'))
                            ):?>
                            <div class="body-menu">
                                <?php else :?>
                                <div class="body-menu hidden-body-menu">
                                    <?php endif?>
                                    <?php $APPLICATION->IncludeComponent(
                                        "bitrix:menu",
                                        "multilevel",
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
                                </div>
                            </div>
                        </div>

                        <div class="right-container-search col-lg-8  col-md-8 ">
                            <div class="search-container">

                                <?php $APPLICATION->IncludeComponent(
                                    "bitrix:search.title",
                                    "visual_aqua",
                                    Array(
                                        "CATEGORY_0" => array("iblock_catalog"),
                                        "CATEGORY_0_TITLE" => "",
                                        "CATEGORY_0_iblock_catalog" => array("5"),
                                        "CATEGORY_1" => array(),
                                        "CATEGORY_1_TITLE" => "",
                                        "CATEGORY_2" => array(),
                                        "CATEGORY_2_TITLE" => "",
                                        "CATEGORY_3" => array(),
                                        "CATEGORY_3_TITLE" => "",
                                        "CATEGORY_4" => array(),
                                        "CATEGORY_4_TITLE" => "",
                                        "CHECK_DATES" => "N",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "CONTAINER_ID" => "title-search",
                                        "INPUT_ID" => "title-search-input",
                                        "NUM_CATEGORIES" => "5",
                                        "ORDER" => "date",
                                        "PAGE" => "#SITE_DIR#catalog/index.php",
                                        "SHOW_INPUT" => "Y",
                                        "SHOW_OTHERS" => "N",
                                        "TOP_COUNT" => "5",
                                        "USE_LANGUAGE_GUESS" => "Y",
                                        "SHOW_PREVIEW" => "Y",
                                    )
                                );?>
                            </div>
                        </div>
                        <?php if(($curPage != "/index.php") && (!CSite::InDir('/catalog/')) || (CSite::InDir('/catalog/product')) || (CSite::InDir('/catalog/compare.php')) ):?>
                        <div class="clearfix"></div>
<?php endif;?>
