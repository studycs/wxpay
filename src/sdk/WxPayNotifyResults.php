<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayNotifyResults
 * @package app\paySdk
 */
class WxPayNotifyResults extends WxPayResults
{
    /**
     * @param $config
     * @param $xml
     * @return WxPayNotifyResults|array|bool
     * @throws WxPayException
     */
    public static function Init($config, $xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        $obj->CheckSign($config);
        return $obj;
    }
}