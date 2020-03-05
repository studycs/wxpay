<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayConfigInterface
 * @package studycs\wxpay\sdk
 * @property $appId
 * @property $merchantId
 * @property $key
 * @property $appSecret
 * @property $sslCertPath
 * @property $sslKeyPath
 */
abstract class WxPayConfigInterface
{
    /**
     * 绑定支付的APPID（必须配置，开户邮件中可查看）
     * @return mixed
     */
    public abstract function GetAppId();
    /**
     * MCHID：商户号（必须配置，开户邮件中可查看）
     * @return mixed
     */
    public abstract function GetMerchantId();

    /**
     * TODO:支付回调url
     * @return mixed
     */
    public abstract function GetNotifyUrl();

    /**
     * 签名和验证签名方式，支持md5和sha256方式
     * @return mixed
     */
    public abstract function GetSignType();

    /**
     * TODO：这里设置代理机器,只有需要代理的时候才设置,不需要代理,请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @param $proxyHost
     * @param $proxyPort
     */
    public abstract function GetProxy(&$proxyHost, &$proxyPort);
    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @return mixed
     */
    public abstract function GetReportLevel();

    /**
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * @return mixed
     */
    public abstract function GetKey();

    /**
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
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