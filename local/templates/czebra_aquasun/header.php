<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$curPage = $APPLICATION->GetCurPage(true);?>
<!doctype html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?
    $APPLICATION->ShowHead();
    
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/bfs/css/czebra.valideted.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/bootstrap.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/front/css/style.css");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery-3.2.1.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.bxslider.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/bootstrap.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/slider.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/menu.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery-ui.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.dotdotdot.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/main.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/jquery.maskedinput.min.js"); //только с подключенным jquery
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.valideted.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.valideted.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/bfs/js/czebra.loading.js"); //только с подключенным jquery
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/front/js/jquery.fancybox.min.js");
    $APPLICATION->AddHeadString('<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" rel="stylesheet" type="text/css"  />');


    ?>
    <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body><div class="page">
<?$APPLICATION->ShowPanel();?>
<header class="header"><div class="container">
    <div class="header-top">
        <div class="mobile-icon-menu col-xs-2"><button></button></div>
        <div class="logo col-lg-3 col-md-3 col-xs-10"><a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/front/images/logo.png"></a></div>
        <div class="top-menu col-lg-5 col-md-5">
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
        <form action="/catalog/" class="search-header col-lg-4 col-md-4">
            <input name="q" type="text" placeholder="поиск товаров">
            <input type="hidden" name="spell" value="1">
            <button><div class="search"></div></button>
        </form>
    </div>

    <div class="mob-menu col-xs-12 hidden-lg hidden-md">
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
        <?$APPLICATION->IncludeComponent(
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
                "SHOW_TOTAL_PRICE" => "N"
            )
        );?>
        <div class="mob-entrance-user col-lg-2 col-md-2">
            <div class="enter"></div>
            <?global $USER;
            if ($USER->IsAuthorized()):?>
                <?$name = (strlen($USER->GetLogin()) > 10) ? substr($USER->GetLogin(),0, 10)."...": $USER->GetLogin();?>
                <a href="/personal/" class="up_n sing"><?=$name?></a>
            <?else:?>
                <a href="/login/?backurl=<?=urlencode($APPLICATION->GetCurPage(false))?>">Вход</a>
            <?endif?>
        </div><br/>
        <div class="mob-phone-header col-lg-5 col-md-5">
            <div class="phone"></div>
            <a href="tel:+74732299621">+7 (473) 229-96-21</a><br/>
        </div>
    </div>

    <div class="header-bottom">
        <div class="catalog-menu-mobile col-xs-12"><ul><li>
            <img src="<?=SITE_TEMPLATE_PATH?>/front/images/expmob.png" class="mob-arrow">Каталог сантехники
        </li></ul></div>

        <div class="catalog-menu col-lg-3 col-md-3 col-xs-12"><ul><li>
            Каталог сантехники<img src="<?=SITE_TEMPLATE_PATH?>/front/images/exp.png" class="dt-arrow">
        </li></ul>
        <div class="wrapp-menu"><div class="catalog-menu-item col-lg-3 col-md-3 col-xs-12">
            <?$APPLICATION->IncludeComponent(
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
        </div></div>
        </div>

        <div class="phone-header col-lg-5 col-md-5">
            <div class="phone"></div>
            <a href="tel:+74732299621" class="link-phone">+7 (473) 229-96-21</a>
            <a href="" id="call-order" class="call-order">Заказать звонок</a>
        </div>
        <div class="entrance-user col-lg-2 col-md-2">
            <div class="enter"></div>
            <?global $USER;
            if ($USER->IsAuthorized()):?>
                <?$name = (strlen($USER->GetLogin()) > 10) ? substr($USER->GetLogin(),0, 10)."...": $USER->GetLogin();?>
                <a href="/personal/" class="up_n sing"><?=$name?></a>
            <?else:?>
                <a href="/login/?backurl=<?=urlencode($APPLICATION->GetCurPage(false))?>">Вход</a>
            <?endif?>
        </div>
        <?$APPLICATION->IncludeComponent(
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
                "SHOW_TOTAL_PRICE" => "N"
            )
        );?>
    </div>
</div></header>

<main class="main"><div class="container">