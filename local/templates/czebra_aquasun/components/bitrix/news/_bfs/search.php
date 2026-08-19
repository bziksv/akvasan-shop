<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(false);

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
?>
<h2>404</h2>
<p>Что-то пошло не так. <a href="/">Предлагаем вернуться на главную страницу</a></p>
