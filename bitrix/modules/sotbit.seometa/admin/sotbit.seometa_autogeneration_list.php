<?
use Sotbit\Seometa\AutogenerationTable;
use Bitrix\Main\Localization\Loc;

// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог

// подключим языковой файл
Loc::loadMessages(__FILE__);

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight("sotbit.seometa");
// если нет прав - отправим к форме авторизации сообщение об ошибке
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

//$CCSeoMeta = new CCSeoMeta();
//if(!$CCSeoMeta->getDemo())
//    return false;

$sTableID = "b_sotbit_seometa_autogeneration"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
$lAdmin = new CAdminUiList($sTableID, $oSort); // основной объект списка

$FilterArr = [
    [
        "id" => "ID",
        "name" =>  'ID',
        "type" => "number",
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ],
    [
        "id" => "NAME",
        "name" =>  Loc::getMessage("SEOMETA_AUTOGENERATION_FILTER_FIND_NAME"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
];

$arFilter= [];

$lAdmin->AddFilter(array_merge($FilterArr, [
    [
        "id" => "%NAME",
        "name" => 'find',
        "type" => "string",
        "quickSearch" => "",
    ]
]), $arFilter);

$arIDs = array_column($FilterArr, "id");

foreach ($arFilter as $key => $item) {
    $findKey = array_search($key, $arIDs);
    if($findKey && $FilterArr[$findKey]['type'] === 'string') {
        $arFilter[$key] = "%" . $item . "%";
    }
}

// ******************************************************************** //
//                ОБРАБОТКА ДЕЙСТВИЙ НАД ЭЛЕМЕНТАМИ СПИСКА              //
// ******************************************************************** //

// сохранение отредактированных элементов
if($lAdmin->EditAction() && $POST_RIGHT == "W")
{
    // пройдем по списку переданных элементов
    foreach($FIELDS as $ID => $arFields)
    {
        if(!$lAdmin->IsUpdated($ID))
            continue;
        
        // сохраним изменения каждого элемента
        $ID = IntVal($ID);
        if($ID > 0)
        {
            foreach($arFields as $key => $value)
                $arData[$key] = $value;
            
            $arData['DATE_CHANGE'] = new \Bitrix\Main\Type\DateTime();
            $result = AutogenerationTable::update($ID, $arData);
            if(!$result->isSuccess())
            {
                $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_SAVE_ERROR"), $ID);
            }
        }
        else
        {
            $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_SAVE_ERROR"), $ID);
        }
    }
}

// обработка одиночных и групповых действий
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT == "W")
{
    // если выбрано "Для всех элементов"
    if($_REQUEST['action_target'] == 'selected')
    {
        $rsData = AutogenerationTable::getList(array(
            'select' => array('ID', 'NAME', 'DATE_CHANGE'),
            'filter' => $arFilter,
            'order' => array($by => $order),
        ));
        while($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }

    // пройдем по списку элементов
    foreach($arID as $ID)
    {
        if(mb_strlen($ID) <= 0)
            continue;
        $ID = IntVal($ID);
        
        // для каждого элемента совершим требуемое действие
        switch($_REQUEST['action'])
        {
            // удаление
            case "delete":
                $result = AutogenerationTable::delete($ID);
                if(!$result->isSuccess())
                {
                    $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE_ERROR"), $ID);
                }
                break;
        }
    }
}



// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //

// выберем список
$rsData = AutogenerationTable::getList(array(
    'select' => array('ID', 'NAME', 'DATE_CHANGE'),
    'filter' => $arFilter,
    'order' => array($by => $order),
));

// преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminUiResult($rsData, $sTableID);

// аналогично CDBResult инициализируем постраничную навигацию
$rsData->NavStart();

// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("rub_nav")));



// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

$lAdmin->AddHeaders(array(
    array(
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ),
    array(
        "id" => "NAME",
        "content" => Loc::getMessage("SEOMETA_AUTOGENERATION_TABLE_NAME"),
        "sort" => "NAME",
        "default" => true,
    ),
    array(
        "id" => "DATE_CHANGE",
        "content" => Loc::getMessage("SEOMETA_AUTOGENERATION_TABLE_DATE_CHANGE"),
        "sort" => "DATE_CHANGE",
        "default" => true,
    ),
));

while($arRes = $rsData->NavNext(true, "f_"))
{
    // создаем строку. результат - экземпляр класса CAdminListRow
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    // далее настроим отображение значений при просмотре и редактировании списка

    // параметр NAME будет редактироваться как текст, а отображаться ссылкой
    $row->AddInputField("NAME", array("size" => 40));
    $row->AddViewField("NAME", '<a href="sotbit.seometa_autogeneration_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');

    // сформируем контекстное меню
    $arActions = Array();

    // редактирование элемента
    $arActions[] = array(
        "ICON" => "edit",
        "DEFAULT" => true,
        "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_EDIT"),
        "ACTION" => $lAdmin->ActionRedirect("sotbit.seometa_autogeneration_edit.php?ID=".$f_ID)
    );

    // удаление элемента
    if ($POST_RIGHT >= "W")
    {
        $arActions[] = array(
            "ICON" => "delete",
            "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE"),
            "ACTION" => "if(confirm('".Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE_CONFIRM")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
        );
    }

    // вставим разделитель
    $arActions[] = array("SEPARATOR" => true);
    // если последний элемент - разделитель, почистим мусор.
    if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
        unset($arActions[count($arActions)-1]);

    // применим контекстное меню к строке
    $row->AddActions($arActions);
}

// резюме таблицы
$lAdmin->AddFooter(
    array(
        array("title" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()), // кол-во элементов
        array("counter" => true, "title" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_CHECKED"), "value" => "0"), // счетчик выбранных элементов
    )
);

// групповые действия
$lAdmin->AddGroupActionTable(Array(
    "delete" => "", // удалить выбранные элементы
    //"activate" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_ACTIVATE"), // активировать выбранные элементы
    //"deactivate" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_DEACTIVATE"), // деактивировать выбранные элементы
));



// ******************************************************************** //
//                АДМИНИСТРАТИВНОЕ МЕНЮ                                 //
// ******************************************************************** //

// сформируем меню из одного пункта - добавление автогенератора
$aContext = array(
    array(
        "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_ADD_TEXT"),
        "LINK" => "sotbit.seometa_autogeneration_edit.php?lang=".LANG,
        "TITLE" => Loc::getMessage("SEOMETA_AUTOGENERATION_ADD_TITLE"),
        "ICON" => "btn_new",
    ),
);

// и прикрепим его к списку
$lAdmin->AddAdminContextMenu($aContext);



// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle(Loc::getMessage("SEOMETA_AUTOGENERATION_TITLE"));

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if( CCSeoMeta::ReturnDemo() == 2){
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}
if( CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0)
{
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO_END")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

$lAdmin->DisplayFilter($FilterArr);
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>