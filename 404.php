<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ошибка 404 - Страница не найдена!");?>    <div class="container-main">
    <div class="title">
        <h1>Ошибка 404</h1>
    </div><br/>
    <p>Что-то пошло не так. Предлагаем вернуться <a href="/">на главную страницу</a>.</p>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>