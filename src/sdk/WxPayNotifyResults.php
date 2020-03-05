<?php


namespace studycs\wxpay\sdk;

/**
 * 回调回包数据基类
 * Class WxPayNotifyResults
 * @package studycs\wxpay\sdk
 */
class WxPayNotifyResults extends WxPayResults
{
    /**
     * @param WxPayConfigInterface $config
     * @param $xml
     * @return array|bool|WxPayNotifyResults
     * @throws WxPayException
     */
    public static function Init($config, $xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        //失败则直接返回失败
        $obj->CheckSign($config);
        return $obj;
    }

}