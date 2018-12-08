<?php
namespace studycs\wxpay\config;
use studycs\wxpay\helper\Html;

class WxPayConfig extends \studycs\wxpay\sdk\WxPayConfig
{

    /**
     * @return mixed
     */
    public function GetAppId()
    {
        return Html::getAppId();
    }

    /**
     * @return mixed
     */
    public function GetMerchantId()
    {
        return Html::getMchId();
    }

    /**
     * @return mixed
     */
    public function GetNotifyUrl()
    {
        return "";
    }

    /**
     * @return mixed
     */
    public function GetSignType()
    {
        return "HMAC-SHA256";
    }

    /**
     * @param $proxyHost
     * @param $proxyPort
     * @return void
     */
    public function GetProxy(&$proxyHost, &$proxyPort)
    {
        $proxyHost = "0.0.0.0";
        $proxyPort = 0;
    }

    /**
     * @return mixed
     */
    public function GetReportLevenl()
    {
        return 1;
    }

    /**
     * @return mixed
     */
    public function GetKey()
    {
        return Html::getKey();
    }

    /**
     * @return mixed
     */
    public function GetAppSecret()
    {
        return Html::getAppSecret();
    }

    /**
     * @param $sslCertPath
     * @param $sslKeyPath
     * @return void
     */
    public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
    {
        $sslCertPath = Html::getCertPath(); // '../cert/apiclient_cert.pem';
        $sslKeyPath  = Html::getKeyPath();  // '../cert/apiclient_key.pem';
    }
}