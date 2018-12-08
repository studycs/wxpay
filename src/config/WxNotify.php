<?php
namespace studycs\wxpay\config;
use studycs\wxpay\sdk\WxPayApi;
use studycs\wxpay\sdk\WxPayNotify;
use studycs\wxpay\sdk\WxPayConfig;
use studycs\wxpay\sdk\WxPayOrderQuery;
use studycs\wxpay\sdk\WxPayNotifyResults;

class WxNotify extends WxPayNotify
{
    /**
     * @param $transaction_id
     * @return bool
     * @throws \studycs\wxpay\sdk\WxPayException
     */
    public function QueryOrder($transaction_id){
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $config = new \studycs\wxpay\config\WxPayConfig();
        $result = WxPayApi::orderQuery($config,$input);
        \Yii::warning('query:'.json_encode($result),'pay');
        if(array_key_exists("return_code",$result) && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    /**
     * @param $xmlData
     */
    public function LogAfterProcess($xmlData){
        \Yii::warning('call back， return xml:'.var_export($xmlData,1),'pay');
        return;
    }

    /**
     * @param WxPayNotifyResults $objData
     * @param WxPayConfig $config
     * @param string $msg
     * @return bool
     * @throws \studycs\wxpay\sdk\WxPayException
     */
    public function NotifyProcess($objData,$config,&$msg){
        $data = $objData->GetValues();
        if(!array_key_exists("return_code",$data)||(array_key_exists("return_code",$data)&&$data['return_code']!="SUCCESS")){
            \Yii::warning('异常异常','pay');
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            \Yii::warning('输入参数不正确','pay');
            return false;
        }
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                \Yii::error('签名错误...','pay');
                return false;
            }
        } catch(\Exception $e) {
            \Yii::error(json_decode($e));
        }
        \Yii::debug('call back:'.json_encode($data));
        if(!$this->Queryorder($data["transaction_id"])){
            \Yii::warning('订单查询失败!','pay');
            return false;
        }
        return true;
    }
}