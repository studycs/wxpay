<?php


namespace studycs\wxpay\sdk;

/**
 * 接口调用结果类
 * Class WxPayResults
 * @package studycs\wxpay\sdk
 */
class WxPayResults extends WxPayDataBase
{
    /**
     * @param WxPayConfigInterface $config
     * @param bool $needSignType
     * @return string
     */
    public function MakeSign($config, $needSignType = false)
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$config->GetKey();
        //签名步骤三：MD5加密或者HMAC-SHA256
        if(strlen($this->GetSign()) <= 32){
            //如果签名小于等于32个,则使用md5验证
            $string = md5($string);
        } else {
            //是用sha256校验
            $string = hash_hmac("sha256",$string ,$config->GetKey());
        }
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    /**
     * @param WxPayConfigInterface $config
     * @return bool
     * @throws WxPayException
     */
    public function CheckSign($config)
    {
        if(!$this->IsSignSet()){
            throw new WxPayException("签名错误！");
        }
        $sign = $this->MakeSign($config, false);
        if($this->GetSign() == $sign){//签名正确
            return true;
        }
        throw new WxPayException("签名错误！");
    }

    /**
     * 使用数组初始化
     * @param $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }

    /**
     * 使用数组初始化对象
     * @param WxPayConfigInterface $config
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
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 将xml转为array
     * @param $config
     * @param $xml
     * @return array|bool
     * @throws WxPayException
     */
    public static function Init($config, $xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        //失败则直接返回失败
        if($obj->values['return_code'] != 'SUCCESS') {
            foreach ($obj->values as $key => $value) {
                #除了return_code和return_msg之外其他的参数存在，则报错
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