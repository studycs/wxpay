<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 17:43
 */

namespace studycs\wxpay\config;
use studycs\wxpay\sdk\WxPayApi;
use studycs\wxpay\sdk\WxPayJsApiPay;
use studycs\wxpay\sdk\WxPayException;

/**
 * @property  int curl_timeout
 */
class JsApiPay
{
    /**
     * @var null $data
     */
    public $data = null;
    public $curl_timeout = 60;

    /**
     * @return mixed
     */
    public function GetOpenid()
    {
        if (!isset($_GET['code'])){
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
            $url = $this->_CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }

    /**
     * @param $UnifiedOrderResult
     * @return false|string
     * @throws WxPayException
     */
    public function GetJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)||!array_key_exists("prepay_id", $UnifiedOrderResult)|| $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);

        $config = new WxPayConfig();
        $jsapi->SetPaySign($jsapi->MakeSign($config));
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        $ch = curl_init();
        $curlVersion = curl_version();
        $config = new WxPayConfig();
        $ua = "WXPaySDK/3.0.9 (".PHP_OS.") PHP/".PHP_VERSION." CURL/".$curlVersion['version']." " .$config->GetMerchantId();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $proxyHost = "0.0.0.0";
        $proxyPort = 0;
        $config->GetProxy($proxyHost, $proxyPort);
        if($proxyHost != "0.0.0.0" && $proxyPort != 0){
            curl_setopt($ch,CURLOPT_PROXY, $proxyHost);
            curl_setopt($ch,CURLOPT_PROXYPORT, $proxyPort);
        }
        $res = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res,true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }

    /**
     * @param $urlObj
     * @return string
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @return false|string
     */
    public function GetEditAddressParameters()
    {
        $config = new WxPayConfig();
        $getData = $this->data;
        $data = array();
        $data["appid"] = $config->GetAppId();
        $data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $time = time();
        $data["timestamp"] = "$time";
        $data["noncestr"] = WxPayApi::getNonceStr();
        $data["accesstoken"] = $getData["access_token"];
        ksort($data);
        $params = $this->ToUrlParams($data);
        $addrSign = sha1($params);

        $afterData = array(
            "addrSign" => $addrSign,
            "signType" => "sha1",
            "scope" => "jsapi_address",
            "appId" => $config->GetAppId(),
            "timeStamp" => $data["timestamp"],
            "nonceStr" => $data["noncestr"]
        );
        $parameters = json_encode($afterData);
        return $parameters;
    }

    /**
     * @param $redirectUrl
     * @return string
     */
    private function _CreateOauthUrlForCode($redirectUrl)
    {
        $config = new WxPayConfig();
        $urlObj["appid"] = $config->GetAppId();
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * @param $code
     * @return string
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $config = new WxPayConfig();
        $urlObj["appid"]  = $config->GetAppId();
        $urlObj["secret"] = $config->GetAppSecret();
        $urlObj["code"]   = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }
}