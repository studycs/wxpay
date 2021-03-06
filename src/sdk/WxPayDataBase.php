<?php
namespace studycs\wxpay\sdk;

/**
 * Class WxPayDataBase
 * @package studycs\wxpay\sdk
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：计算/设置/获取签名、输出xml格式的参数、从xml读取数据对象等
 */
class WxPayDataBase
{
    protected $values = [];

    /**
     * 设置签名，详见签名生成算法类型
     * @param $sign_type
     * @return mixed
     */
    public function SetSignType($sign_type){
        $this->values['sign_type'] = $sign_type;
        return $sign_type;
    }

    /**
     * 设置签名，详见签名生成算法
     * @param WxPayConfigInterface $config
     * @return mixed
     * @throws WxPayException
     */
    public function SetSign($config){
        $sign = $this->MakeSign($config);
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return mixed
     */
    public function GetSign(){
        return $this->values['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return bool
     */
    public function IsSignSet(){
        return array_key_exists('sign', $this->values);
    }

    /**
     * @return string
     * @throws WxPayException
     */
    public function ToXml(){
        if(!is_array($this->values) || count($this->values) <= 0)
        {
            throw new WxPayException("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * @param $xml
     * @return mixed
     * @throws WxPayException
     */
    public function FromXml($xml){
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * @return string
     */
    public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @param WxPayConfigInterface $config
     * @param bool $needSignType
     * @return string
     * @throws WxPayException
     */
    public function MakeSign($config, $needSignType = true)
    {
        if($needSignType) {
            $this->SetSignType($config->GetSignType());
        }
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$config->GetKey();
        //签名步骤三：MD5加密或者HMAC-SHA256
        if($config->GetSignType() == "MD5"){
            $string = md5($string);
        } else if($config->GetSignType() == "HMAC-SHA256") {
            $string = hash_hmac("sha256",$string ,$config->GetKey());
        } else {
            throw new WxPayException("签名类型不支持！");
        }
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    /**
     * 获取设置的值
     */
    public function GetValues()
    {
        return $this->values;
    }

}