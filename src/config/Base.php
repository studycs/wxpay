<?php
namespace studycs\wxpay\config;
/**
 * Class Base
 * @package app\paySdk
 */
class  Base
{
    const KEY   = '10imZ9BQ7ofmgkeQ2lKPPwymNSZ0HUaw';
    const MCHID = '1263709801';
    const RPURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
    const APPID = 'wx3fcdce24657ca31e';
    const CODEURL   = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
    const OPENIDURL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
    const SECRET    = '3bdafdf3ae1a020c632c0c9ec23d4fca';
    /**
     * 获取签名
     * @param array $arr
     * @return string
     */
    public function getSign($arr)
    {
        $arr = array_filter($arr);
        if (isset($arr['sign'])) {
            unset($arr['sign']);
        }
        ksort($arr);
        $str = $this->arrToUrl($arr) . '&key=' . self::KEY;
        return strtoupper(md5($str));
    }

    /**
     * 获取带签名的数组
     * @param array $arr
     * @return array
     */
    public function setSign($arr)
    {
        $arr['sign'] = $this->getSign($arr);;
        return $arr;
    }

    /**
     * 数组转URL格式的字符串
     * @param array $arr
     * @return string
     */
    public function arrToUrl($arr)
    {
        return urldecode(http_build_query($arr));
    }

    /**
     * 数组转xml
     * @param $arr
     * @return string
     */
    function ArrToXml($arr)
    {
        if (!is_array($arr) || count($arr) == 0) return '';
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * Xml转数组
     * @param $xml
     * @return mixed|string
     */
    function XmlToArr($xml)
    {
        if ($xml == '') return '';
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    /**
     * @param $url
     * @param $postfields
     * @return mixed
     */
    function postData($url,$postfields)
    {
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;
        $params[CURLOPT_HEADER] = false;
        $params[CURLOPT_RETURNTRANSFER] = true;
        $params[CURLOPT_FOLLOWLOCATION] = true;
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        $params[CURLOPT_SSLCERTTYPE] = 'PEM';
        $params[CURLOPT_SSLCERT]     = __DIR__.'/../certificate/apiclient_cert.pem';
        $params[CURLOPT_SSLKEYTYPE]  = 'PEM';
        $params[CURLOPT_SSLKEY]      = __DIR__.'/../certificate/apiclient_key.pem';
        curl_setopt_array($ch,$params);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}