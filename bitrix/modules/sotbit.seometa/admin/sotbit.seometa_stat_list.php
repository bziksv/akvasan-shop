<?

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sotbit\Seometa\Orm\ConditionTable;
use Sotbit\Seometa\Orm\SeometaStatisticsTable;
use Bitrix\Main\Text\Emoji;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$id_module = 'sotbit.seometa';
Loader::includeModule($id_module);

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("sotbit.seometa");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
Loc::loadMessages(__FILE__);

$sTableID = "b_sotbit_seometa_statistics";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminUIList($sTableID, $oSort);

function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;
    if ($_REQUEST['del_filter'] == 'Y')
        return false;
    return count($lAdmin->arFilterErrors) == 0;
}

$FilterArr = [
    [
        "id" => "ID",
        "name" => 'ID',
        "type" => "number",
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ],
    [
        "id" => "CONDITION_ID",
        "name" => Loc::getMessage("SEO_META_FILTER_CONDITION_ID"),
        "type" => "number",
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ],
    [
        "id" => "SITE_ID",
        "name" => Loc::getMessage("SEO_META_FILTER_SITE_ID"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "URL",
        "name" => Loc::getMessage("SEO_META_FILTER_LINK"),
        "type" => "string",
        "quickSearch" => "",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "IN_SITEMAP",
        "name" => Loc::getMessage("SEO_META_IN_SITEMAP"),
        "type" => "list",
        "items" => array(
            '' => Loc::getMessage("SEO_META_IN_SITEMAP_NO_MATTER"),
            "Y" => Loc::getMessage("SEO_META_POST_YES"),
            "N" => Loc::getMessage("SEO_META_POST_NO"),
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "NO_INDEX",
        "name" => Loc::getMessage("SEO_META_NO_INDEX"),
        "type" => "list",
        "items" => array(
            '' => Loc::getMessage("SEO_META_IN_SITEMAP_NO_MATTER"),
            "Y" => Loc::getMessage("SEO_META_POST_YES"),
            "N" => Loc::getMessage("SEO_META_POST_NO"),
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "ROBOTS_INFO",
        "name" => Loc::getMessage("SEO_META_ROBOT_TYPE"),
        "type" => "list",
        "items" => array(
            '' => Loc::getMessage("SEO_META_IN_SITEMAP_NO_MATTER"),
            "YandexBot" => Loc::getMessage("SEO_META_YANDEX_BOT"),
            "GoogleBot" => Loc::getMessage("SEO_META_GOOGLE_BOT"),
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "PAGE_STATUS",
        "name" => Loc::getMessage("SEO_META_PAGE_STATUS"),
        "type" => "list",
        "items" => array(
            "" => "",
            "200" => "200",
            "404" => "404",
        ),
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "DATE_CREATE",
        "name" => Loc::getMessage("SEO_META_TIME"),
        "type" => "date",
        "filterable" => "",
        "default" => true
    ],
    [
        "id" => "LAST_DATE_CHECK",
        "name" => Loc::getMessage("SEO_META_LAST_CHECK"),
        "type" => "date",
        "filterable" => "",
        "default" => true
    ],
];


$arFilter = [];

$lAdmin->AddFilter(array_merge($FilterArr, [
    [
        "id" => "%URL",
        "name" => 'find',
        "type" => "string",
        "quickSearch" => "",
    ]
]), $arFilter);

$arIDs = array_column($FilterArr, "id");

foreach ($arFilter as $key => $item) {
    $findKey = array_search($key, $arIDs);
    if ($findKey && $FilterArr[$findKey]['type'] === 'string') {
        $arFilter[$key] = "%" . $item . "%";
    }
    if($findKey && $FilterArr[$findKey]['id'] === 'ROBOTS_INFO') {
        $arFilter['ROBOTS_INFO'] = "%{$arFilter['ROBOTS_INFO']}%";
    }
}

if ($lAdmin->EditAction()) {
    foreach ($FIELDS as $ID => $arFields) {
        $TYPE = mb_substr($ID, 0, 1);
        $ID = intval(mb_substr($ID, 1));

        if (!$lAdmin->IsUpdated($ID))
            continue;

        $ID = IntVal($ID);
        if ($ID > 0) {
            if ($TYPE == "P") {
                foreach ($arFields as $key => $value)
                    $arData[$key] = $value;
                $result = SeometaStatisticsTable::update($ID, $arData);
                if (!$result->isSuccess()) {
                    $lAdmin->AddGroupError(GetMessage("SEO_META_SAVE_ERROR") . " " . GetMessage("SEO_META_NO_ZAPIS"), $ID);
                }
            }
        } else {
            $lAdmin->AddGroupError(GetMessage("SEO_META_SAVE_ERROR") . " " . GetMessage("SEO_META_NO_ZAPIS"), $ID);
        }
    }
}

if ($arID = $lAdmin->GroupAction()) {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = SeometaStatisticsTable::getList([
            'select' => ['*'],
            'filter' => $arFilter,
            'order' => [$by => $order],
        ]);
        while ($arRes = $rsData->Fetch()) {
            $arRes["T"] = "S";
            $arRes['ID'] = "P" . $arRes['ID'];
            $arID[] = $arRes['ID'];
        }
    }

    foreach ($arID as $ID) {
        $TYPE = mb_substr($ID, 0, 1);
        $ID = intval(mb_substr($ID, 1));

        if (mb_strlen($ID) <= 0)
            continue;
        $ID = IntVal($ID);

        switch ($_REQUEST['action']) {
            case "delete":
                if ($TYPE == "P") {
                    $result = SeometaStatisticsTable::delete($ID);
                    if (!$result->isSuccess()) {
                        $lAdmin->AddGroupError(GetMessage("SEO_META_DEL_ERROR") . " " . GetMessage("SEO_META_NO_ZAPIS"), $ID);
                    }
                }
                break;
        }
    }
}

$rsData = SeometaStatisticsTable::getList([
    'select' => ['*'],
    'filter' => $arFilter,
    'order' => [$by => $order],
]);
$arResult = [];
while ($arRes = $rsData->Fetch()) {
    $arRes["T"] = "P";
    $arResult[] = $arRes;
}
$rs = new CDBResult;
$rs->InitFromArray($arResult);
$rsData = new CAdminUIResult($rs, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("SEO_META_NAV")));
$lAdmin->AddHeaders([
    ["id" => "ID",
        "content" => GetMessage("SEO_META_TABLE_ID"),
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ],
    ["id" => "URL",
        "content" => GetMessage("SEO_META_TABLE_URL"),
        "sort" => "URL",
        "default" => true,
    ],
    ["id" => "DATE_CREATE",
        "content" => GetMessage("SEO_META_TABLE_DATE_CREATE"),
        "sort" => "DATE_CREATE",
        "default" => true,
    ],
    ["id" => "SITE_ID",
        "content" => GetMessage("SEO_META_TABLE_SITE_ID"),
        "sort" => "SITE_ID",
        "default" => true,
    ],
    ["id" => "META_TITLE",
        "content" => GetMessage("SEO_META_TABLE_META_TITLE"),
        "default" => true,
    ],
    ["id" => "META_KEYWORDS",
        "content" => GetMessage("SEO_META_TABLE_META_KEYWORDS"),
        "default" => true,
    ],
    ["id" => "META_DESCRIPTION",
        "content" => GetMessage("SEO_META_TABLE_META_DESCRIPTION"),
        "default" => true,
    ],
    ["id" => "IN_SITEMAP",
        "content" => GetMessage("SEO_META_TABLE_IN_SITEMAP"),
        "sort" => "IN_SITEMAP",
        "default" => true,
    ],
    ["id" => "NO_INDEX",
        "content" => GetMessage("SEO_META_TABLE_NO_INDEX"),
        "sort" => "NO_INDEX",
        "default" => true,
    ],
    ["id" => "CONDITION_NAME",
        "content" => GetMessage("SEO_META_TABLE_CONDITION_NAME"),
        "sort" => "CONDITION_ID",
        "default" => true,
    ],
    ["id" => "ROBOTS_INFO_GOOGLE",
        "content" => GetMessage("SEO_META_TABLE_ROBOTS_INFO_GOOGLE"),
        "default" => true,
    ],
    ["id" => "ROBOTS_INFO_YANDEX",
        "content" => GetMessage("SEO_META_TABLE_ROBOTS_INFO_YANDEX"),
        "default" => true,
    ],
    ["id" => "PAGE_STATUS",
        "content" => GetMessage("SEO_META_TABLE_PAGE_STATUS"),
        "default" => true,
    ],
    ["id" => "LAST_DATE_CHECK",
        "content" => GetMessage("SEO_META_TABLE_LAST_DATE_CHECK"),
        "sort" => "LAST_DATE_CHECK",
        "default" => true,
    ],
    ["id" => "SORT",
        "content" => GetMessage("SEO_META_TABLE_SORT"),
        "sort" => "SORT",
        "default" => true,
    ],
]);
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/sotbit.seometa_stat_list.php'));
while ($arRes = $rsData->NavNext(true, "f_")):
    $row =& $lAdmin->AddRow($f_T . $f_ID, $arRes);
    $row->AddInputField("SORT", ["size" => 20]);
    if ($arRes['CONDITION_ID'] != null)
        $name = ConditionTable::getById($arRes['CONDITION_ID'])->fetch();
    else
        $name = null;
    if ($name)
        $name = '<a href="/bitrix/admin/sotbit.seometa_edit.php?ID=' . $name['ID'] . '&lang=' . LANG . '" target="_blank">#' . $name['ID'] . ' ' . $name['NAME'] . '</a>';
    else $name = '';
    $row->AddViewField('CONDITION_NAME', $name);

    if ($metaTitle = unserialize($arRes['META_TITLE'])) {
        $title = $metaTitle;
    } else {
        $title = null;
    }
    if ($metaKeyWords = unserialize($arRes['META_KEYWORDS'])) {
        $keyWords = $metaKeyWords;
    } else {
        $keyWords = null;
    }
    if ($metaDescription = unserialize($arRes['META_DESCRIPTION'])) {
        $description = $metaDescription;
    } else {
        $description = null;
    }


    $title = $title['COINCIDENCE'] === 'Y' ? Loc::getMessage("SEO_META_POST_COINCIDENCE", ['#CONTENT#' => Emoji::decode($title['CONTENT'])])
        : Loc::getMessage("SEO_META_POST_NO_COINCIDENCE", ['#CONTENT#' => Emoji::decode($title['CONTENT'])]);
    $keyWords = $keyWords['COINCIDENCE'] === 'Y' ? Loc::getMessage("SEO_META_POST_COINCIDENCE", ['#CONTENT#' => Emoji::decode($keyWords['CONTENT'])])
        : Loc::getMessage("SEO_META_POST_NO_COINCIDENCE", ['#CONTENT#' => Emoji::decode($keyWords['CONTENT'])]);
    $description = $description['COINCIDENCE'] === 'Y' ? Loc::getMessage("SEO_META_POST_COINCIDENCE", ['#CONTENT#' => Emoji::decode($description['CONTENT'])])
        : Loc::getMessage("SEO_META_POST_NO_COINCIDENCE", ['#CONTENT#' => Emoji::decode($description['CONTENT'])]);

    $row->AddViewField('META_TITLE', $title);
    $row->AddViewField('META_KEYWORDS', $keyWords);
    $row->AddViewField('META_DESCRIPTION', $description);

    $row->AddViewField('IN_SITEMAP', $arRes['IN_SITEMAP'] == 'Y' ? Loc::getMessage("SEO_META_POST_YES") : Loc::getMessage("SEO_META_POST_NO"));
    $row->AddViewField('NO_INDEX', $arRes['NO_INDEX'] == 'Y' ? Loc::getMessage("SEO_META_POST_YES") : Loc::getMessage("SEO_META_POST_NO"));

    if ($unserialRobots = unserialize($arRes['ROBOTS_INFO'])) {
        $googleBot = $unserialRobots['GoogleBot'];
        $yandexBot = $unserialRobots['YandexBot'];
    }
    $googleBot['CHECK'] = $googleBot['CHECK'] === 'Y' ? Loc::getMessage("SEO_META_BOTS_YES", ["#TIME#" => $googleBot['TIME_CHECK']])
        : Loc::getMessage("SEO_META_POST_NO");
    $yandexBot['CHECK'] = $yandexBot['CHECK'] === 'Y' ? Loc::getMessage("SEO_META_BOTS_YES", ["#TIME#" => $yandexBot['TIME_CHECK']])
        : Loc::getMessage("SEO_META_POST_NO");
    $row->AddViewField('ROBOTS_INFO_GOOGLE', $googleBot['CHECK']);
    $row->AddViewField('ROBOTS_INFO_YANDEX', $yandexBot['CHECK']);

    if (($arRes['PAGE_STATUS'] >= 200 && $arRes['PAGE_STATUS'] <= 299)) {
        $pageStatus = Loc::getMessage("SEO_META_TABLE_PAGE_STATUS_OK", ["#STATUS#" => $arRes['PAGE_STATUS']]);
    } elseif (($arRes['PAGE_STATUS'] >= 400 && $arRes['PAGE_STATUS'] <= 599)) {
        $pageStatus = Loc::getMessage("SEO_META_TABLE_PAGE_STATUS_ERROR", ["#STATUS#" => $arRes['PAGE_STATUS']]);
    } elseif (($arRes['PAGE_STATUS'] >= 300 && $arRes['PAGE_STATUS'] <= 399)) {
        $pageStatus = Loc::getMessage("SEO_META_TABLE_PAGE_STATUS_REDIRECT", ["#STATUS#" => $arRes['PAGE_STATUS']]);
    }
    $row->AddViewField('PAGE_STATUS', $pageStatus);

    $arActions = [];

    if ($POST_RIGHT >= "W")
        $arActions[] = [
            "ICON" => "delete",
            "TEXT" => GetMessage("SEO_META_DEL"),
            "ACTION" => "if(confirm('" . GetMessage('SEO_META_DEL_CONF') . "')) " . $lAdmin->ActionDoGroup($f_T . $f_ID, "delete")
        ];

    $arActions[] = ["SEPARATOR" => true];
    if (is_set($arActions[count($arActions) - 1], "SEPARATOR"))
        unset($arActions[count($arActions) - 1]);

    $row->AddActions($arActions);
endwhile;

$lAdmin->AddFooter(
    [
        ["title" => GetMessage("SEO_META_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()],
        ["counter" => true, "title" => GetMessage("SEO_META_LIST_CHECKED"), "value" => "0"],
    ]
);

$lAdmin->AddGroupActionTable([
    "delete" => GetMessage("SEO_META_LIST_DELETE"),
    "for_all" => true
]);

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("SEO_META_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if (CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_DEMO_END") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

$rsSites = CSite::GetList(
    $by = "sort",
    $order = "desc",
    [
        "ACTIVE" => "Y"
    ]
);

while ($arSite = $rsSites->Fetch()) {
    if (Option::get("sotbit.seometa", 'INC_STATISTIC', 'N', $arSite['LID']) === 'Y') {
        $activeStat[$arSite['LID']] = Option::get("sotbit.seometa", 'INC_STATISTIC', 'N', $arSite['LID']);
    }
}
if (!$activeStat) { ?>
    <div class="adm-info-message-wrap">
        <div class="adm-info-message"><?= GetMessage("SEO_META_NOTE_FOR_WORK") ?></div>
    </div>
<? } ?>
<?

if (CCSeoMeta::ReturnDemo() == 2) {
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?= Loc::getMessage("SEO_META_DEMO") ?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}

$lAdmin->DisplayFilter($FilterArr);
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>