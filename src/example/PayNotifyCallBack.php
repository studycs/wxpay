<?php
namespace studycs\wxpay\example;
use studycs\wxpay\sdk\WxPayApi;
use studycs\wxpay\sdk\WxPayNotify;
use studycs\wxpay\sdk\WxPayException;
use studycs\wxpay\sdk\WxPayOrderQuery;
use studycs\wxpay\sdk\WxPayNotifyResults;
use studycs\wxpay\sdk\WxPayConfigInterface;

/**
 * Class PayNotifyCallBack
 * @package studycs\wxpay\example
 */
class PayNotifyCallBack extends WxPayNotify
{
    /**
     * @param $transaction_id
     * @return bool
     * @throws WxPayException
     */
    public function QueryOrder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $config = new WxPayConfig();
        $result = WxPayApi::orderQuery($config, $input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {
        Log::DEBUG("call back， return xml:" . $xmlData);
        return;
    }

    //重写回调处理函数

    /**
     * @param WxPayNotifyResults $objData
     * @param WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return bool
     * @throws WxPayException
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data) || (array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            $msg = "异常异常";
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                Log::ERROR("签名错误...");
                return false;
            }
        } catch(\Exception $e) {
            Log::ERROR(json_encode($e));
        }
        //TODO 3、处理业务逻辑
        Log::DEBUG("call back:" . json_encode($data));
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
}