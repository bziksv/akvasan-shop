<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
CModule::IncludeModule('highloadblock');

class getHlInfo {
    public function id($name) {

		$hlblock = HL\HighloadBlockTable::getList([
		  'filter' => ['=NAME' => $name]
		])->fetch();

		$hlblock_id = $hlblock["ID"];

        return $hlblock_id;
    }
    public function class($id) {
	    if (empty($id))
	    {
	        return false;
	    }

	    if ($id < 1)
	    {
			$id = getHlInfo::id( $id );
    	}

	    if (empty($id) || $id < 1)
	    {
	        return false;
	    }

	    $hlblock = HL\HighloadBlockTable::getById($id)->fetch();
	    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
	    $entity_data_class = $entity->getDataClass();

	    return $entity_data_class;
    }
}

function showAllQuickLinksButton($tags) {
    $isShowTarget = 2;

    if(count($tags) > $isShowTarget) {
        return true;
    }

    return false;
}
