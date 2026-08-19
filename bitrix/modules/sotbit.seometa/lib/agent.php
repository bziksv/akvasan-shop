<?

namespace Sotbit\Seometa;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Sotbit\Seometa\Helper\SiteMapGenerate;
use Sotbit\Seometa\Orm\SeometaStatisticsTable;
use Bitrix\Main\Loader;

class Agent
{

    private static string $moduleId = "sotbit.seometa";

    public static function xmlWriterAgentChpuWithRegenerate($ID)
    {
        global $USER;
        $USER = new \CUser;

        Loader::includeModule(self::$moduleId);

        SiteMapGenerate::setSite($ID);
        $site = SiteMapGenerate::getSite();

        $result = SiteMapGenerate::generateHeavy($ID);

        if ($result['SUCCESS'] === 'Y' && Loader::includeModule('sotbit.regions')) {
            \Sotbit\Regions\Seo\File::generateRegionsSitemap($site['SITE_ID']);
        }

        return $result['NAME'];
    }

    public static function xmlWriterAgentChpuNotRegenerate($ID)
    {
        global $USER;
        $USER = new \CUser;

        Loader::includeModule(self::$moduleId);

        SiteMapGenerate::setSite($ID);
        $site = SiteMapGenerate::getSite();

        $result = SiteMapGenerate::generateLight($ID);

        if ($result['SUCCESS'] === 'Y' && Loader::includeModule('sotbit.regions')) {
            \Sotbit\Regions\Seo\File::generateRegionsSitemap($site['SITE_ID']);
        }

        return $result['NAME'];
    }

    public static function actualizedSeoMetaStatAgent($siteID, $offset)
    {
        $limit = Option::get("sotbit.seometa", 'AGENT_LIMIT_STATISTIC', '10', $siteID);
        //TODO: need update, when check stat off in general settings?
        if (!Loader::includeModule(self::$moduleId))
            return "\Sotbit\Seometa\Agent::actualizedSeoMetaStatAgent('$siteID', $offset);";

        $statModule = Loader::includeModule("statistic");
        if ($statModule) {
            $proactive = \COption::GetOptionString("statistic", "DEFENCE_ON");
            if ($proactive === 'Y') {
                \COption::SetOptionString("statistic", "DEFENCE_ON", "N");
            }
        }

        $rsStat = SeometaStatisticsTable::getList([
            'select' => [
                'ID',
                'URL',
                'META_TITLE',
                'META_KEYWORDS',
                'META_DESCRIPTION',
            ],
            'filter' => ['SITE_ID' => $siteID],
            'order' => ['ID'],
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        if (!$rsStat) {
            $offset = 0;
            if ($statModule && $proactive === 'Y') {
                \COption::SetOptionString("statistic", "DEFENCE_ON", "Y");
            }
            return "\Sotbit\Seometa\Agent::actualizedSeoMetaStatAgent('$siteID', $offset);";
        }

        foreach ($rsStat as $stat) {
            $httpClient = new \Bitrix\Main\Web\HttpClient;
            $httpClient->setRedirect(false);
            $httpClient->post($stat['URL'], ["SEOMETA_STATUS_AGENT" => "Y", "SEOMETA_STATUS_CODE" => "Y", "SEOMETA_STAT_ID" => $stat["ID"]]);
            $status = $httpClient->getStatus();

            $arFields = [
                'PAGE_STATUS' => $status,
                'LAST_DATE_CHECK' => new DateTime()
            ];

            SeometaStatisticsTable::update($stat['ID'], $arFields);
            $offset++;
        }
        //$time = strtotime((new DateTime())->add('10 SEC')) - strtotime($arAgentActualized['NEXT_EXEC']);
        //that variable get from func CAgent::ExecuteAgents() in which call eval() for agent, and need if offset more than set limit
        global $pPERIOD;
        $pPERIOD = '0';

        if ($statModule && $proactive === 'Y') {
            \COption::SetOptionString("statistic", "DEFENCE_ON", "Y");
        }

        return "\Sotbit\Seometa\Agent::actualizedSeoMetaStatAgent('$siteID', $offset);";
    }
}

?>