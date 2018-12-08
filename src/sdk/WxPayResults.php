<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayResults
 * @package app\paySdk
 */
class WxPayResults extends WxPayDataBase
{
    /**
     * @param $config
     * @param bool $needSignType
     * @return string
     */
    public function MakeSign($config, $needSignType = false)
    {
        ksort($this->values);
        $string = $this->ToUrlParams();
        $string = $string . "&key=".$config->GetKey();
        if(strlen($this->GetSign()) <= 32){
            $string = md5($string);
        } else {
            $string = hash_hmac("sha256",$string ,$config->GetKey());
        }
        $result = strtoupper($string);
        return $result;
    }

    /**
     * @param $config
     * @return bool
     * @throws WxPayException
     */
    public function CheckSign($config)
    {
        if(!$this->IsSignSet()){
            throw new WxPayException("签名错误！");
        }
        $sign = $this->MakeSign($config, false);
        if($this->GetSign() == $sign){
            return true;
        }
        throw new WxPayException("签名错误！");
    }

    /**
     * @param $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }

    /**
     * @param $config
     * @param $array
     * @param bool $noCheckSign
     * @return WxPayResults
     * @throws WxPayException
     */
    public static function InitFromArray($config, $array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->FromArray($array);
        if($noCheckSign == false){
            $obj->CheckSign($config);
        }
        return $obj;
    }

    /**
     *
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * @param $config
     * @param $xml
     * @return array|bool
     * @throws WxPayException
     */
    public static function Init($config, $xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        if($obj->values['return_code'] != 'SUCCESS') {
            foreach ($obj->values as $key => $value) {
                if($key != "return_code" && $key != "return_msg"){
                    throw new WxPayException("输入数据存在异常！");
                }
            }
            return $obj->GetValues();
        }
        $obj->CheckSign($config);
        return $obj->GetValues();
    }
}