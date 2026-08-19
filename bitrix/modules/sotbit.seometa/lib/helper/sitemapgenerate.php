<?php

namespace Sotbit\Seometa\Helper;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Sotbit\Seometa\Condition\Rule;
use Sotbit\Seometa\Link\XmlWriter;
use Sotbit\Seometa\Orm\ConditionTable;
use Sotbit\Seometa\Orm\SeometaUrlTable;
use Sotbit\Seometa\Orm\SitemapTable;

class SiteMapGenerate
{
    private static array|false $siteTable = false;

    public static function setSite($id)
    {
        self::$siteTable = SitemapTable::getById($id)->fetch();
    }

    public static function getSite()
    {
        return self::$siteTable;
    }

    public static function generateLight($ID)
    {
        $nameNonChpu = "\Sotbit\Seometa\Agent::xmlWriterAgentChpuNotRegenerate($ID);";
        $seometaSitemap = new \CSeoMetaSitemapLight();
        $seometaSitemap->setRequestData('ID', $ID);
        $arSitemap = self::$siteTable ?: SitemapTable::getById($seometaSitemap->getRequestData('ID'))->fetch();

        $sitePaths = $seometaSitemap->pathMainSitemap($ID);
        if (!is_array($arSitemap) || $sitePaths['TYPE'] == 'ERROR') {
            return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
        } else {
            $arSitemap['SETTINGS'] = unserialize($arSitemap['SETTINGS']);
        }

        $arSitemapProto = !empty($arSitemap['SETTINGS']['PROTO']) ? 'https://' : 'http://';

        $SiteUrl = '';
        if ($sitePaths['domain_dir']) {
            $SiteUrl = $sitePaths['domain_dir'];
        } else {
            return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
        }

        $mainSitemapName = '';
        if (!empty($arSitemap['SETTINGS']['FILENAME_INDEX'])) {
            $mainSitemapName = $arSitemap['SETTINGS']['FILENAME_INDEX'];
        } else {
            return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
        }

        if (!empty($arSitemap['SETTINGS']['FILTER_TYPE'])) {
            $FilterTypeKey = key($arSitemap['SETTINGS']['FILTER_TYPE']);
            $FilterCHPU = $arSitemap['SETTINGS']['FILTER_TYPE'][$FilterTypeKey];
            $FilterType = mb_strtolower($FilterTypeKey . ((!$FilterCHPU) ? '_not' : '') . '_chpu');
        } else {
            return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
        }

        $mainSitemap = $sitePaths['abs_path'] . $mainSitemapName;
        if (file_exists($mainSitemap)) {
            if ((new BackupMethods)->makeBackup($sitePaths['abs_path']) == '') {
                $seometaSitemap->deleteOldSeometaSitemaps($sitePaths['abs_path']);
            }

            $arrConditionsParams = ConditionTable::getConditionBySiteId($sitePaths['site_id']);
            $filter = ['ACTIVE' => 'Y', 'SITE_ID' => $sitePaths['site_id'], '!=PARENT_CONDITION.NO_INDEX' => 'Y'];
            if ($arSitemap['SETTINGS']['EXCLUDE_NOT_SEF'] == 'Y' && is_array($filter['CONDITION_ID'])) {
                foreach ($arrConditionsParams as $conditionParam) {
                    $filter['CONDITION_ID'] = array_merge($filter['CONDITION_ID'],
                        [$conditionParam['ID']]);
                }
            }

            $seometaSitemap->markUrlsExcludeSitemap($sitePaths['site_id']);
            $arrUrls = SeometaUrlTable::getList([
                'select' => [
                    'ID',
                    'NEW_URL',
                    'REAL_URL',
                    'DATE_CHANGE',
                    'CONDITION_ID',
                    'PARENT_CONDITION.NO_INDEX'
                ],
                'filter' => $filter,
                'order' => ['ID'],
            ])->fetchAll();
            if (empty($arrUrls)) {
                return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
            } else {
                $countChpuLinks = count($arrUrls);
                $sitemapIndex = 1;
                $sitemapFileName = $sitePaths['abs_path'] . 'sitemap_seometa_' . $ID . '_';
                $countIter = 0;

                $xmlMethods = new XMLMethods();
                $countNumberSymbForChange = $countChpuLinks * 2; //count bytes numbers in tags
                $countUrlSymbForChange = ($countChpuLinks * 2) * 3; //count bytes which need for change numbers in tags to the 'url', 3 that count symb in 'url'
                $version = 38; //count bytes which place version tag
                $urlset = 69; //count bytes which place urlset tag
                foreach ($arrUrls as $keyLink => $link) {
                    $conditionParams = ConditionTable::getConditionById($link['CONDITION_ID']);
                    SeometaUrlTable::update($link['ID'],
                        ['IN_SITEMAP' => 'Y']
                    );

                    if (!isset($conditionParams['PRIORITY'])) {
                        $conditionParams['PRIORITY'] = '0.0';
                    } else {
                        $conditionParams['PRIORITY'] = number_format($conditionParams['PRIORITY'],
                            1);
                    }

                    $sitemapFiles[$sitemapFileName . $sitemapIndex . '.xml'][] = [
                        '_c' => [
                            'loc' => [
                                '_v' => $arSitemapProto . $SiteUrl . ($link['NEW_URL'] ?: $link['REAL_URL'])
                            ],
                            'lastmod' => [
                                '_v' => $link['DATE_CHANGE']->format("Y-m-d\TH:i:sP")
                            ],
                            'changefreq' => [
                                '_v' => $conditionParams['CHANGEFREQ'] ?: 'always'
                            ],
                            'priority' => [
                                '_v' => $conditionParams['PRIORITY']
                            ]
                        ]
                    ];

                    $countIter += 1;
                    $countItem = count($sitemapFiles[$sitemapFileName . $sitemapIndex . '.xml']);
                    $currentXMLSize = (strlen($xmlMethods->ary2xml($sitemapFiles[$sitemapFileName . $sitemapIndex . '.xml'])) - $countNumberSymbForChange + $countUrlSymbForChange + $version + $urlset) / 1000000;
                    if ($countItem == Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_COUNT_LINKS', '50000', $arSitemap['SITE_ID'])) {
                        $sitemapIndex++;
                    } elseif ($currentXMLSize >= Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_FILE_SIZE', '50', $arSitemap['SITE_ID'], 'Mb')) {
                        $lastValue = array_pop($sitemapFiles[$sitemapFileName . $sitemapIndex . '.xml']);
                        $sitemapIndex++;
                        $sitemapFiles[$sitemapFileName . $sitemapIndex . '.xml'] = [$lastValue];
                    }
                }

                foreach ($sitemapFiles as $keySitemap => $sitemap) {
                    $data = $xmlMethods->createXml($keySitemap);
                    if (!empty($data['TYPE'])) {
                        $result = $data;
                    }

                    $xmlMethods->ins2ary($data['urlset']['_c']['url'],
                        $sitemap,
                        count($data['urlset']['_c']['url']));

                    $xmlData = $xmlMethods->ary2xml($data);
                    $xmlMethods->writeSiteMap($keySitemap, $xmlData);
                }

                if (!empty($sitePaths['abs_path'])) {
                    $xml = file_get_contents($sitePaths['abs_path'] . $sitePaths['file_name']);
                    if (empty($xml)) {
                        $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex></sitemapindex>';
                    }
                    $data = $xmlMethods->xml2ary($xml);

                    if (is_array($data['sitemapindex']['_c']['sitemap'])) {
                        $xmlMethods->delSeometaFromMainSitemap($data['sitemapindex']['_c']['sitemap']);
                    }

                    $item = $xmlMethods->seometaMainSitemapFiles(count($sitemapFiles),
                        $ID,
                        $sitePaths['url']);

                    if (!empty($item) && is_array($item)) {
                        $count = $data['sitemapindex']['_c']['sitemap'] ? count($data['sitemapindex']['_c']['sitemap']) : 0;
                        $xmlMethods->ins2ary($data['sitemapindex']['_c']['sitemap'],
                            $item,
                            $count);

                        $xmlData = $xmlMethods->ary2xml($data);
                        $writeStatus = $xmlMethods->writeSiteMap($sitePaths['abs_path'] . $sitePaths['file_name'],
                            $xmlData);

                        if (!empty($writeStatus['TYPE'])) {
                            $result = $writeStatus;
                        }
                    }

                } else {
                    $result = $sitePaths;
                }
                $dateRun = new DateTime();
                $result['DATE_RUN'] = $dateRun->toString();
                SitemapTable::update($ID,
                    ['DATE_RUN' => $dateRun]);

                return ['SUCCESS' => 'Y', 'NAME' => $nameNonChpu];
            }
        }
        return ['SUCCESS' => 'N', 'NAME' => $nameNonChpu];
    }

    public static function generateHeavy($ID)
    {
        libxml_use_internal_errors(true);
        $arSitemap = self::$siteTable ?: SitemapTable::getById($ID)->fetch();

        $nameAgentChpu = "\Sotbit\Seometa\Agent::xmlWriterAgentChpuWithRegenerate({$ID});";
        if (empty($arSitemap)) {
            return ['SUCCESS' => 'N', 'NAME' => $nameAgentChpu];
        } else {
            $arSitemap['SETTINGS'] = unserialize($arSitemap['SETTINGS']);
        }

        $rsSites = \CSite::GetById($arSitemap['SITE_ID']);
        $arSite = $rsSites->Fetch();
        $arSitemapProto = !empty($arSitemap['SETTINGS']['PROTO']) ? 'https://' : 'http://';
        $SiteUrl = $arSitemapProto;

        if (!empty($arSitemap['SETTINGS']['DOMAIN'])) {
            $SiteUrl .= $arSitemap['SETTINGS']['DOMAIN'];
        } else {
            return ['SUCCESS' => 'N', 'NAME' => $nameAgentChpu];
        }

        if (!empty($arSitemap['SETTINGS']['FILENAME_INDEX'])) {
            $mainSitemapName = $arSitemap['SETTINGS']['FILENAME_INDEX'];
        } else {
            return ['SUCCESS' => 'N', 'NAME' => $nameAgentChpu];
        }

        $mainSitemapUrl = $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $mainSitemapName;

        $seometaSitemap = new \CSeoMetaSitemapLight();
        if ((new BackupMethods)->makeBackup($arSite['ABS_DOC_ROOT'] . $arSite['DIR']) == '') {
            $seometaSitemap->deleteOldSeometaSitemaps($arSite['ABS_DOC_ROOT'] . $arSite['DIR']);
        }

        $link = Linker::getInstance();

        $seometaUrlCollection = SeometaUrlTable::getList(['select' => ['*'], 'filter' => ['SITE_ID' => $arSitemap['SITE_ID']]])->fetchCollection();
        $elements = $seometaUrlCollection->getAll();
        foreach ($elements as $element) {
            $element->set('IN_SITEMAP', false);
        }
        $seometaUrlCollection->save();

        $condAndSect = $link->getConditionList($arSite['LID']);
        if (!empty($condAndSect)) {
            $conditionIDs = $condAndSect['conditions'];

            $rsCondition = ConditionTable::getList([
                'select' => [
                    'ID',
                    'DATE_CHANGE',
                    'INFOBLOCK',
                    'STRONG',
                    'NO_INDEX',
                    'RULE',
                    'SITES',
                    'SECTIONS',
                    'PRIORITY',
                    'CHANGEFREQ',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    '!=NO_INDEX' => 'Y',
                    'ID' => $conditionIDs
                ],
                'order' => [
                    'ID' => 'asc'
                ]
            ])->fetchAll();

            $writer = XmlWriter::getInstance(
                $ID,
                $arSite['ABS_DOC_ROOT'] . $arSite['DIR'],
                $SiteUrl,
                $arSitemap['SITE_ID'],
                false,
                true
            );

            if (!empty($arSitemap['SETTINGS']['DOMAIN'])) {
                $SiteUrl .= mb_substr($arSite['DIR'], 0, -1);
                $SiteUrl[strlen($SiteUrl) - 1] !== '/' ? $SiteUrl .= '/' : '';
            } else {
                return ['SUCCESS' => 'N', 'NAME' => $nameAgentChpu];
            }

            foreach ($rsCondition as $cond) {
                $conditionSites = unserialize($cond['SITES']);
                if (is_array($conditionSites) && in_array($arSitemap['SITE_ID'], $conditionSites)) {
                    $rule = unserialize($cond['RULE']);
                    if (empty($rule['CHILDREN'])) {
                        continue;
                    }
                    $conditionSections = unserialize($cond['SECTIONS']);
                    $link->generate(
                        $writer,
                        $cond['ID'],
                        $conditionSections
                    );
                    $link->setRule(new Rule());
                }
            }

            $res = $writer->WriteEnd();
            if(!$res) {
                $seometaSitemap = new \CSeoMetaSitemapLight();
                $seometaSitemap->initRequestData();
                $seometaSitemap->deleteOldSeometaSitemaps($arSite['ABS_DOC_ROOT'] . $arSite['DIR']);
                $xml = file_get_contents($mainSitemapUrl);
                $data = (new XMLMethods)->xml2ary($xml);
                if (is_array($data['sitemapindex']['_c']['sitemap'])) {
                    (new XMLMethods)->delSeometaFromMainSitemap($data['sitemapindex']['_c']['sitemap']);
                }
                $xmlData = (new XMLMethods)->ary2xml($data);
                $writeStatus = (new XMLMethods)->writeSiteMap($mainSitemapUrl, $xmlData);
                return ['SUCCESS' => 'N', 'NAME' => $nameAgentChpu];
            }

            SitemapTable::update($ID, ['DATE_RUN' => new DateTime()]);

            //work with mainsitemap
            if ($writer->getAddID() > 0) {
                $xml = file_get_contents($mainSitemapUrl);
                if (empty($xml)) {
                    $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex></sitemapindex>';
                }
                $xmlMethods = new XMLMethods();
                $data = $xmlMethods->xml2ary($xml);

                if (is_array($data['sitemapindex']['_c']['sitemap'])) {
                    $xmlMethods->delSeometaFromMainSitemap($data['sitemapindex']['_c']['sitemap']);
                }

                for ($i = 0; $i < count((array)$xml->sitemap); $i++) {
                    if (
                        isset($xml->sitemap[$i]->loc)
                        && mb_strpos($xml->sitemap[$i]->loc, $SiteUrl . "sitemap_seometa_") !== false
                    ) {
                        $xml->sitemap[$i]->loc = '';
                    }
                }

                $item = $xmlMethods->seometaMainSitemapFiles(
                    $writer->getAddID(),
                    $ID,
                    $SiteUrl
                );

                if (is_array($item) && !empty($item)) {
                    $count = $data['sitemapindex']['_c']['sitemap'] ? count($data['sitemapindex']['_c']['sitemap']) : 0;
                    $xmlMethods->ins2ary(
                        $data['sitemapindex']['_c']['sitemap'],
                        $item,
                        $count
                    );
                    $xmlData = $xmlMethods->ary2xml($data);
                    $xmlMethods->writeSiteMap($mainSitemapUrl, $xmlData);
                }
            }
        }
        return ['SUCCESS' => 'Y', 'NAME' => $nameAgentChpu];
    }
}