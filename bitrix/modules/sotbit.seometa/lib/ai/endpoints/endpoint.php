<?php

namespace Sotbit\Seometa\AI\EndPoints;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Web\Http\Method;
use Bitrix\Main\Web\Json;

abstract class EndPoint
{
    /**
     * @var EndpointsContainer
     */
    protected $container;

    protected string $proxyAddress;
    protected string $proxyPort;
    protected string $proxyLogin;
    protected string $proxyPassword;

    public function __construct(EndpointsContainer $container)
    {
        $this->container = $container;
        if ($container->mainSettings['PROXY_ADDRESS']) {
            $this->proxyAddress = $container->mainSettings['PROXY_ADDRESS'];
            $this->proxyPort = $container->mainSettings['PROXY_PORT'];
            $this->proxyLogin = $container->mainSettings['PROXY_LOGIN'];
            $this->proxyPassword = $container->mainSettings['PROXY_PASSWORD'];
        }
    }

    public function getModelVersion()
    {
        return [
            'Sotbit\Seometa\AI\Model\ChatGPT' => [
                'gpt-3.5-turbo' => 'gpt-3.5-turbo',
                'gpt-4' => 'gpt-4'
            ],
            'Sotbit\Seometa\AI\Model\Gigachat' => [
                'GigaChat' => 'GigaChat',
                'GigaChat:latest' => 'GigaChat:latest',
                'GigaChat-Plus' => 'GigaChat-Plus',
                'GigaChat-Pro' => 'GigaChat-Pro'
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function sendPostRequest(string $url, array $headers, $params = [])
    {
        $httpClient = new \Bitrix\Main\Web\HttpClient();
        $httpClient->setHeaders($headers);

        if (isset($this->proxyAddress) && $this->proxyAddress) {
            $httpClient->setProxy($this->proxyAddress, $this->proxyPort, $this->proxyLogin, $this->proxyPassword);
        }

        $httpClient->disableSslVerification();
        $response = $httpClient->post($url, $params);
        if ($httpClient->getStatus() != 200) {
            $error = $httpClient->getStatus();
            try {
                if (!empty($response) && $arResponse = Json::decode($response)) {
                    $error .= ' - ' . ($arResponse['message'] ?: $arResponse['error']['message']);
                }
            } catch (\Throwable) {
            }

            $error .= ($httpClient->getError() ? (implode(',', $httpClient->getError())) : '');
            throw new \Exception($error);
        } else {
            return Json::decode($response);
        }
    }

    public function sendGetRequest(string $url, array $headers, array $params = [])
    {
        $httpClient = new \Bitrix\Main\Web\HttpClient;
        $url .= '?' . http_build_query($params);
        $httpClient->setHeaders($headers);

        if (isset($this->proxyAddress) && $this->proxyAddress) {
            $httpClient->setProxy($this->proxyAddress, $this->proxyPort, $this->proxyLogin, $this->proxyPassword);
        }

        $httpClient->disableSslVerification();
        $response = $httpClient->get($url);

        if ($httpClient->getStatus() != 200) {
            throw new \Exception($httpClient->getStatus() . ' - ' . implode(',', $httpClient->getError()));
        } else {
            return json_decode(Encoding::convertEncodingToCurrent($response), true);
        }
    }

    public function render(\CAdminForm $tabControl, array $requestValues)
    {
        $modelVersion = $this->getModelVersion();
        $mainSettings = $this->container->mainSettings;

        $tabControl->AddDropDownField(
            'MAIN_SETTINGS[MODEL_VERSION]',
            Loc::getMessage('SEO_META_AI_MODEL_VERSION'),
            true,
            $modelVersion[static::class],
            $requestValues['MAIN_SETTINGS']['MODEL_VERSION'] ?: $mainSettings['MODEL_VERSION'] ?: 0
        );
    }
}