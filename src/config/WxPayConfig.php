<?php
namespace studycs\wxpay\config;

class WxPayConfig extends \studycs\wxpay\sdk\WxPayConfig
{

    /**
     * @return mixed
     */
    public function GetAppId()
    {
        return 'wx3fcdce24657ca31e';
    }

    /**
     * @return mixed
     */
    public function GetMerchantId()
    {
        return '1263709801';
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
        return '10imZ9BQ7ofmgkeQ2lKPPwymNSZ0HUaw';
    }

    /**
     * @return mixed
     */
    public function GetAppSecret()
    {
        return '3bdafdf3ae1a020c632c0c9ec23d4fca';
    }

    /**
     * @param $sslCertPath
     * @param $sslKeyPath
     * @return void
     */
    public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
    {
        $sslCertPath = '../cert/apiclient_cert.pem';
        $sslKeyPath = '../cert/apiclient_key.pem';
    }
}