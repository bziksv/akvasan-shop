<?php
$moduleID = "czebra.smtp";
$landPref = "CZEBRA_SMTP_";

Bitrix\Main\Loader::includeModule($moduleID);

if (!$USER->CanDoOperation('czebra.smtp_settings')) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

//$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arAllOptions = Array(
	Array("active", GetMessage($landPref.'active'), array("checkbox"), ""),
	Array("from", GetMessage($landPref.'from'), array("text"), ""),
	Array("psw", GetMessage($landPref.'psw'), array("text"), ""),
	Array("host", GetMessage($landPref.'host'), array("text"), ""),
	Array("port", GetMessage($landPref.'port'), array("text"), ""),
);


$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage($landPref.'TAB_NAME'), "ICON" => "", "TITLE" => GetMessage($landPref.'TAB_NAME_TITLE')),
	array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "unitpay_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && check_bitrix_sessid()) {
	if (strlen($RestoreDefaults) > 0) {
		COption::RemoveOption($moduleID);
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($moduleID, array($zr["ID"]));
	} else {
		COption::SetOptionString($moduleID, 'active', $_POST['active']);
		COption::SetOptionString($moduleID, 'from', $_POST['from']);
		COption::SetOptionString($moduleID, 'psw', $_POST['psw']);
		COption::SetOptionString($moduleID, 'host', $_POST['host']);
		COption::SetOptionString($moduleID, 'port', $_POST['port']);
	}
}


$tabControl->Begin();?>
<form
	method="POST"  enctype="multipart/form-data"
	action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>"
	name="antispampro_settings">
	<?=bitrix_sessid_post();?>
<?
$tabControl->BeginNextTab();
?>
<?
foreach($arAllOptions as $arOption):
	$val = COption::GetOptionString($moduleID, $arOption[0], $arOption[3]);
	$type = $arOption[2];

?>
<tr>
	<td width="40%" nowrap><?
	//if ($type[0] == "checkbox")
		echo "<label for=\"".htmlspecialcharsbx($arOption[0])."\">".$arOption[1].":</label>";
	/*else
		echo $arOption[1];*/
?> </td>
	<td width="60%"><?
	if($type[0]=="checkbox"):
		?><input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?> /><?
	elseif ($type[0]=="text"):
		?><input type="text" size="<?echo $type[1]?>" maxlength="2550" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" style="width:50%;" />
		<?
	elseif($type[0]=="textarea"):
		?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" style="width:90%;"><?echo htmlspecialcharsbx($val)?></textarea><?
	endif;
	?></td>
</tr>
<?endforeach;?>

<?
$tabControl->BeginNextTab();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
$tabControl->Buttons();?>
<input type="submit" name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirmRestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?
$tabControl->End();
?>
</form>
