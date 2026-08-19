<?php
namespace Czebra\BFS;

class AjaxLoading
{
    public static function getCryptArray($name, $templateName, $params)
    {
        foreach ($params as $key=>$value) {
            if (strripos($key, "PAGER") !== false) {
                unset($params[$key]);
            } elseif($key[0] == "~") {
                unset($params[$key]);
            }
        }
        $params["COMPONENT_NAME"] = $name;
        $params["TEMPLATE_NAME"] = $templateName;
        if ($params["SHOW_ALL_WO_SECTION"] == "1") {
            $params["SHOW_ALL_WO_SECTION"] = "Y";
        }
        $result = urlencode(base64_encode(gzcompress(json_encode($params))));
        return $result;
    }

    public static function getDecryptArray($strCrypt)
    {
        $result = json_decode(gzuncompress(base64_decode($strCrypt)));
        return json_decode(json_encode($result), true);
    }
}
