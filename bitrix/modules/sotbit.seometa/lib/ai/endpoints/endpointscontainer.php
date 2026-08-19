<?php

namespace Sotbit\Seometa\AI\EndPoints;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class EndpointsContainer
{
    /**
     * Array with the cached endpoints
     * @var Endpoint[]
     */
    protected array $endpoints = [];

    /**
     * Array with the ai settings
     */
    public array $mainSettings = [];

    public function __construct()
    {
        $this->mainSettings = $this->getMainSettings();
    }

    public function __get(string $endpoint): Endpoint
    {
        return $this->getEndpoint($endpoint);
    }

    public function getEndpoint(string $endpoint): Endpoint
    {
        if (!isset($this->endpoints[$endpoint])) {
            $this->addEndpoint($endpoint);
        }

        return $this->endpoints[$endpoint];
    }

    protected function addEndpoint(string $endpoint)
    {
        $class = 'Sotbit\Seometa\AI\Model' . '\\' . ucfirst($endpoint);

        if (!$this->checkIsEndpoint($class)) {
            throw new \Exception(Loc::getMessage('SEO_META_AI_SYSTEM_MESSAGE'));
        }

        $this->endpoints[$endpoint] = new $class($this);
    }

    protected function checkIsEndpoint(string $class): bool
    {
        if(!class_exists($class)) return false;

        return in_array(EndPoint::class, class_parents($class));
    }

    public static function getMainSettings()
    {
        if ($unserialize = unserialize(Option::get('sotbit.seometa', 'MAIN_SETTINGS'))) {
            return $unserialize;
        }

        return [];
    }

    public static function getAiModels()
    {
        $modelDir = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . \CCSeoMeta::MODULE_ID . '/lib/ai/model/';
        if ($dir = opendir($modelDir)) {
            while (false !== $item = readdir($dir)) {
                if ($item == '..' || $item == '.') {
                    continue;
                }

                $current = self::parseClassName($modelDir . $item);
                if ($current !== false) {
                    list($namespace, $class) = $current;
                    $arClasses[$class] = $class;
                }
            }
            closedir($dir);
        }

        return $arClasses;
    }

    private static function parseClassName(string $filePath)
    {
        $tokens = token_get_all(file_get_contents($filePath));
        $nsStart = false;
        $classStart = false;
        $namespace = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_ABSTRACT) {
                return false;
            }
            if ($token[0] === T_CLASS) {
                $classStart = true;
            }
            if ($classStart && ($token[0] === T_STRING || $token[0] === T_NAME_QUALIFIED)) {
                return [$namespace, $token[1]];
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }

            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }

            if ($nsStart && $token[0] === T_NAME_QUALIFIED) {
                $namespace = $token[1];
            }

            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
            }
        }

        return false;
    }
}