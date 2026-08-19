<?php

namespace Sotbit\Seometa\AI\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use Matrix\Exception;
use Sotbit\Seometa\AI\EndPoints\EndPoint;

class Gigachat extends EndPoint
{
    const SCOPE = [
        'GIGACHAT_API_PERS',
        'GIGACHAT_API_CORP'
    ];

    /**
     * @throws \Exception
     */
    public function getToken()
    {
        $url = 'https://ngw.devices.sberbank.ru:9443/api/v2/oauth';

        if (strpos($this->container->mainSettings['API_KEY'], '­') !== false) {
            $this->container->mainSettings['API_KEY'] = preg_replace('/\x{00AD}/u', '', $this->container->mainSettings['API_KEY']);
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => $this->guidv4(),
            'Authorization' => "Basic {$this->container->mainSettings['API_KEY']}"
        ];

        $params['scope'] = $this->container->mainSettings['SCOPE'] ?: 'GIGACHAT_API_PERS';

        try {
            $token = $this->sendPostRequest($url, $headers, $params);
        } catch (\Exception $e) {
            return null;
        }

        return $token;
    }

    public function getListModels()
    {
        $url = 'https://gigachat.devices.sberbank.ru/api/v1/models';

        $token = $this->getToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        return $this->sendGetRequest($url, $headers);
    }

    public function sendMessage($message, $freeRequest = false)
    {
        $url = 'https://gigachat.devices.sberbank.ru/api/v1/chat/completions';

        $token = $this->getToken();
        $mainSettings = $this->container->mainSettings;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        if ($freeRequest) {
            $params = [
                'model' => $mainSettings['MODEL_VERSION'],
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $message
                    ],
                ],
                'temperature' => (float)$mainSettings['TEMPERATURE'] ?? 1.0,
                'max_tokens' => (int)$mainSettings['MAX_TOKENS'] ?? 1024,
            ];
        } else {
            $params = [
                'model' => $mainSettings['MODEL_VERSION'],
                'messages' => [
                    /*[
                         'role' => 'system',
                         'content' => Loc::getMessage('SEO_META_AI_SYSTEM_MESSAGE')
                     ],*/
                    [
                        'role' => 'user',
                        'content' => $message
                    ],
                ],
                'temperature' => (float)$mainSettings['TEMPERATURE'] ?? 1.0,
                'max_tokens' => (int)$mainSettings['MAX_TOKENS'] ?? 1024,
            ];
        }

        try {
            $response = $this->sendPostRequest($url, $headers, Json::encode($params));
            return $response['choices'][0]["message"]["content"];
        } catch (\Exception $e) {
            throw new \Exception ($e->getMessage());
        }
    }

    public function getTokensCount($message)
    {
        $url = 'https://gigachat.devices.sberbank.ru/api/v1/tokens/count';

        $token = $this->getToken();
        $mainSettings = $this->container->mainSettings;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        $params = [
            'model' => $mainSettings['MODEL_VERSION'],
            'input' => [$message],
        ];

        try {
            $response = $this->sendPostRequest($url, $headers, Json::encode($params));
            return $response[0]['tokens'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function render(\CAdminForm $tabControl, array $requestValues)
    {
        parent::render($tabControl, $requestValues);

        $mainSettings = $this->container->mainSettings;

        $tabControl->AddEditField(
            'MAIN_SETTINGS[API_KEY]',
            Loc::getMessage('SEO_META_AI_API_KEY_GIGACHAT'),
            true,
            [
                "size" => 35,
                "maxlength" => 255
            ],
            $requestValues['MAIN_SETTINGS']['API_KEY'] ?: $mainSettings['API_KEY'] ?: ''
        );

        $tabControl->BeginCustomField("MAX_TOKENS", Loc::getMessage("SEO_META_AI_MAX_TOKENS"), true);
        echo '
            <tr id="tr_MAX_TOKENS">
                <td width="40%">
                    <div class="form-control-hint">
                        <span data-hint="' . Loc::getMessage('SEO_META_AI_MAX_TOKEN_HINT_GIGACHAT') . '" data-hint-html></span>
                        ' . $tabControl->GetCustomLabelHTML() . '
                    </div>
                 </td>
                <td width="60%">
                    <input type="number" id="max_tokens" name="MAIN_SETTINGS[MAX_TOKENS]" value="' . ($requestValues['MAIN_SETTINGS']['MAX_TOKENS'] ?: $mainSettings['MAX_TOKENS'] ?: 1024) . '" step="1" min="0"/>
                </td>
            </tr>';
        $tabControl->EndCustomField("MAX_TOKENS");

        $tabControl->BeginCustomField("TEMPERATURE", Loc::getMessage("SEO_META_AI_TEMPERATURE"), true);
        echo '
            <tr id="tr_TEMPERATURE">
                 <td width="40%">
                    <div class="form-control-hint">
                        <span data-hint="' . Loc::getMessage('SEO_META_AI_TEMPERATURE_HINT_GIGACHAT') . '" data-hint-html></span>
                        ' . $tabControl->GetCustomLabelHTML() . '
                    </div>
                 </td>
                 <td width="60%">
                    <input type="number"  id="temperature"  name="MAIN_SETTINGS[TEMPERATURE]" value="' . ($requestValues['MAIN_SETTINGS']['TEMPERATURE'] ?: $mainSettings['TEMPERATURE'] ?: 1.0) . '" step="any" min="0"/>
                </td>
            </tr>';
        $tabControl->EndCustomField("TEMPERATURE");

        $scopeValue = $requestValues['MAIN_SETTINGS']['SCOPE'] ?: $mainSettings['SCOPE'] ?: 0;
        $tabControl->BeginCustomField("SCOPE", Loc::getMessage("SEO_META_AI_MODEL_SCOPE_GIGACHAT"), true);
        echo '
             <tr id="tr_TEMPERATURE">
                 <td width="40%">
                    <div class="form-control-hint">
                        <span data-hint="' . Loc::getMessage('SEO_META_AI_MODEL_SCOP_HINT_GIGACHAT') . '" data-hint-html></span>
                        ' . $tabControl->GetCustomLabelHTML() . '
                    </div>
                 </td>
                 <td width="60%">
                    <select name="MAIN_SETTINGS[SCOPE]"> ';
        foreach (self::SCOPE as $scope) {
            $selected = $scopeValue === $scope ? 'selected' : '';
            echo '<option value="' . $scope . '"  ' . $selected . '>' . $scope . '</option>';
        }
        echo '</select>
                </td>
            </tr>';
        $tabControl->EndCustomField("SCOPE");
    }

    public function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}