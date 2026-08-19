<?

namespace Sotbit\Seometa\Link;

use Bitrix\Iblock\Template\Engine;
use Bitrix\Iblock\Template\Entity\Section;
use Sotbit\Seometa\SeoMetaMorphy;

class TagWriter extends AbstractWriter
{
    private static $Writer = false;
    private $WorkingConditions;

    private $countTagWrite;
    private $countTagWrited = 0;
    private $elementTag = 0;

    private function __construct($WorkingConditions, $countTagsForWrite, $elementTag = 0)
    {
        $this->WorkingConditions = $WorkingConditions;
        $this->countTagWrite = $countTagsForWrite;
        $this->elementTag = $elementTag;
    }

    public static function getInstance($WorkingConditions, $countTagsForWrite, $elementTag = 0)
    {
        if (self::$Writer === false) {
            self::$Writer = new TagWriter($WorkingConditions, $countTagsForWrite, $elementTag);
        }

        return self::$Writer;
    }

    public function AddRow(array $arFields) {

    }

    public function Write(array $arFields)
    {
        SeoMetaMorphy::init($arFields['section_id'], $arFields['properties']);
        $conditionTag = $this->elementTag ? $this->arCondition['ELEMENT_TAG'] : $this->arCondition['TAG'];
        if ($arFields['strict_relinking'] != 'Y') {
            if ($this->elementTag) {
                $Title = SeoMetaMorphy::processMorphy($this->arCondition['ELEMENT_TAG']);
            } else {
                $Title = SeoMetaMorphy::processMorphy($this->arCondition['TAG']);
            }
        } elseif (in_array($this->arCondition['ID'], $this->WorkingConditions) && $conditionTag) {
            $Title = SeoMetaMorphy::processMorphy($conditionTag);
        }
        $rsSites = \CSite::GetById($arFields['site_id']);
        $arSite = $rsSites->Fetch();
        $arSiteDir = substr($arSite['DIR'], 0, -1);
        if (!empty($Title)) {
            $Title = SeoMetaMorphy::convertMorphy($Title);
            $this->data[] = [
                'URL' => trim($arSiteDir . $arFields['real_url']),
                'REAL_URL' => trim($arSiteDir . $arFields['real_url']),
                'SORT' => '100',
                'TITLE' => trim($Title),
                'PRODUCT_COUNT' => $arFields['product_count'],
                'SITE_ID' => $arFields['site_id'],
                'CONDITION_ID' => $arFields['condition_id'],
                'PROPERTIES' => $arFields['properties'],
            ];
            return true;
        }
        return false;
    }

    public function getData()
    {
        return $this->data;
    }
}
