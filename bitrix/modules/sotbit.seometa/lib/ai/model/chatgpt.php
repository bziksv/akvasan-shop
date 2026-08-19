<?php

namespace Sotbit\Seometa\AI\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use Sotbit\Seometa\AI\EndPoints\EndPoint;

class ChatGPT extends EndPoint
{
    public function getListModels()
    {
        $url = "https://api.openai.com/v1/models";

        $headers = [
            'Authorization' => "Bearer {$this->container->mainSettings['API_KEY']}"
        ];

        return $this->sendGetRequest($url, $headers);
    }

    public function sendMessage($message, $freeRequest = false)
    {
        $url = "https://api.openai.com/v1/chat/completions";

        $mainSettings =  $this->container->mainSettings;

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$mainSettings['API_KEY']}"
        ];

        if($freeRequest) {
            $params = [
                'model' => $mainSettings['MODEL_VERSION'],
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],
                'temperature' => (float)$mainSettings['TEMPERATURE'] ?? 1.0,
                'max_tokens' => (int)$mainSettings['MAX_TOKENS'] ?? 1024,
            ];
        } else {
            $params = [
                'model' => $mainSettings['MODEL_VERSION'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => Loc::getMessage('SEO_META_AI_SYSTEM_MESSAGE')
                    ],
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],
                'temperature' => (float)$mainSettings['TEMPERATURE'] ?? 1.0,
                'max_tokens' => (int)$mainSettings['MAX_TOKENS'] ?? 1024,
            ];
        }

        try {
            $res = $this->sendPostRequest($url, $headers, Json::encode($params));
            return $res['choices'][0]["message"]["content"];
        } catch (\Exception $e) {
            throw new \Exception ($e->getMessage());
        }
    }

    public function render(\CAdminForm $tabControl, array $requestValues)
    {
        parent::render($tabControl, $requestValues);

        $mainSettings = $this->container->mainSettings;

        $tabControl->AddEditField(
            'MAIN_SETTINGS[API_KEY]',
            Loc::getMessage('SEO_META_AI_API_KEY_CHATGPT'),
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
                        <span data-hint="' . Loc::getMessage('SEO_META_AI_MAX_TOKEN_HINT_CHATGPT'). '" data-hint-html></span>
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
                        <span data-hint="' . Loc::getMessage('SEO_META_AI_TEMPERATURE_HINT_CHATGPT'). '" data-hint-html></span>
                        ' . $tabControl->GetCustomLabelHTML() . '
                    </div>
                 </td>
                 <td width="60%">
                    <input type="number" id="temperature" name="MAIN_SETTINGS[TEMPERATURE]" value="' . ($requestValues['MAIN_SETTINGS']['TEMPERATURE'] ? round($requestValues['MAIN_SETTINGS']['TEMPERATURE'], 1) : ($mainSettings['TEMPERATURE'] ? round($mainSettings['TEMPERATURE'], 1) : 1)) . '" step="any" min="0"/>
                </td>
            </tr>';
        $tabControl->EndCustomField("TEMPERATURE");

        $tabControl->AddEditField(
            'MAIN_SETTINGS[PROXY_ADDRESS]',
            Loc::getMessage('SEO_META_AI_PROXY_ADDRESS'),
            false,
            [
                "size" => 35,
                "maxlength" => 255
            ],
            $requestValues['MAIN_SETTINGS']['PROXY_ADDRESS'] ?: $mainSettings['PROXY_ADDRESS'] ?: ''
        );

        $tabControl->AddEditField(
            'MAIN_SETTINGS[PROXY_PORT]',
            Loc::getMessage('SEO_META_AI_PROXY_PORT'),
            false,
            [
                "size" => 35,
                "maxlength" => 255
            ],
            $requestValues['MAIN_SETTINGS']['PROXY_PORT'] ?: $mainSettings['PROXY_PORT'] ?: ''
        );

        $tabControl->AddEditField(
            'MAIN_SETTINGS[PROXY_LOGIN]',
            Loc::getMessage('SEO_META_AI_PROXY_LOGIN'),
            false,
            [
                "size" => 35,
                "maxlength" => 255
            ],
            $requestValues['MAIN_SETTINGS']['PROXY_LOGIN'] ?: $mainSettings['PROXY_LOGIN'] ?: ''
        );

        $tabControl->AddEditField(
            'MAIN_SETTINGS[PROXY_PASSWORD]',
            Loc::getMessage('SEO_META_AI_PROXY_PASSWORD'),
            false,
            [
                "size" => 35,
                "maxlength" => 255
            ],
            $requestValues['MAIN_SETTINGS']['PROXY_PASSWORD'] ?: $mainSettings['PROXY_PASSWORD'] ?: ''
        );
    }
}