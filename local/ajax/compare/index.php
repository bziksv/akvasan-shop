<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

use Bitrix\Main\Application;
use Czebra\Compare;

$request = Application::getInstance()->getContext()->getRequest();

if($request['action'] == 'add' && intval($request['id']) > 0) {
    echo Compare::Add($request['id']);
} elseif($request['action'] == 'list') {
    echo Compare::List();
} elseif($request['action'] == 'delete' && intval($request['id']) > 0) {
    echo Compare::Delete($request['id']);
}