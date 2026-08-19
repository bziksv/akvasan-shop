<?php

IncludeModuleLangFile(__FILE__);

class czebra_smtp extends CModule
{
    var $MODULE_ID = "czebra.smtp";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	function czebra_smtp()
	{
		$MODULE_LANG_PREFIX = "CZEBRA_SMTP_";
		
		$arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
		$this->MODULE_NAME = GetMessage($MODULE_LANG_PREFIX.'MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage($MODULE_LANG_PREFIX.'MODULE_DESCRIPTION');
		$this->PARTNER_NAME = GetMessage($MODULE_LANG_PREFIX.'NAME_COMPANY');
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_URI = 'http://www.czebra.ru/';
    }

	function DoInstall()
	{
		RegisterModule($this->MODULE_ID);		
		return true;
    }
	
	function DoUninstall()
	{		
		UnRegisterModule($this->MODULE_ID);
		return true;
    }

}
