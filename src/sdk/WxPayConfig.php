<?php
namespace studycs\wxpay\sdk;
abstract class WxPayConfig
{
    /**
     * @return mixed
     */
	public abstract function GetAppId();

    /**
     * @return mixed
     */
	public abstract function GetMerchantId();

    /**
     * @return mixed
     */
	public abstract function GetNotifyUrl();

    /**
     * @return mixed
     */
	public abstract function GetSignType();

    /**
     * @param $proxyHost
     * @param $proxyPort
     * @return mixed
     */
	public abstract function GetProxy(&$proxyHost, &$proxyPort);

    /**
     * @return mixed
     */
	public abstract function GetReportLevenl();

    /**
     * @return mixed
     */
	public abstract function GetKey();

    /**
     * @return mixed
     */
	public abstract function GetAppSecret();

    /**
     * @param $sslCertPath
     * @param $sslKeyPath
     * @return mixed
     */
	public abstract function GetSSLCertPath(&$sslCertPath, &$sslKeyPath);
}
