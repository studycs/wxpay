<?php
namespace studycs\wxpay\example;
use studycs\wxpay\sdk\WxPayApi;
use studycs\wxpay\sdk\WxPayBizPayUrl;
use studycs\wxpay\sdk\WxPayUnifiedOrder;

class NativePay
{
    /**
     *
     * 生成扫描支付URL,模式一
     * @param $productId
     * @return string
     */
    public function GetPrePayUrl($productId)
    {
        $biz = new WxPayBizPayUrl();
        $biz->SetProduct_id($productId);
        $values = null;
        try{
            $config = new WxPayConfig();
            $values = WxpayApi::bizpayurl($config, $biz);
        } catch(\Exception $e) {
            Log::ERROR(json_encode($e));
        }
        return "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
    }

    /**
     *
     * 参数数组转换为url参数
     * @param array $urlObj
     * @return string
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param WxPayUnifiedOrder $input
     * @return bool|mixed
     */
    public function GetPayUrl($input)
    {
        if($input->GetTrade_type() == "NATIVE")
        {
            try{
                $config = new WxPayConfig();
                return WxPayApi::unifiedOrder($config, $input);
            } catch(\Exception $e) {
                Log::ERROR(json_encode($e));
            }
        }
        return false;
    }

}