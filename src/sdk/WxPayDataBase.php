<?php
namespace studycs\wxpay\sdk;

class WxPayDataBase
{
    protected $values = array();

    /**
     * @param $sign_type
     * @return mixed
     */
    public function SetSignType($sign_type)
    {
        $this->values['sign_type'] = $sign_type;
        return $sign_type;
    }

    /**
     * @param $config
     * @return string
     * @throws WxPayException
     */
    public function SetSign($config)
    {
        $sign = $this->MakeSign($config);
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * @return mixed
     */
    public function GetSign()
    {
        return $this->values['sign'];
    }

    /**
     * @return bool
     */
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values);
    }

    /**
     * @return string
     * @throws WxPayException
     */
    public function ToXml()
    {
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
     * @return array|mixed
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 格式化参数格式化成url参数
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
     * @param WxPayConfig $config
     * @param bool $needSignType
     * @return string
     * @throws WxPayException
     */
    public function MakeSign($config, $needSignType = true)
    {
        if($needSignType) {
            $this->SetSignType($config->GetSignType());
        }
        ksort($this->values);
        $string = $this->ToUrlParams();
        $string = $string . "&key=".$config->GetKey();
        if($config->GetSignType() == "MD5"){
            $string = md5($string);
        } else if($config->GetSignType() == "HMAC-SHA256") {
            $string = hash_hmac("sha256",$string ,$config->GetKey());
        } else {
            throw new WxPayException("签名类型不支持！");
        }
        $result = strtoupper($string);
        return $result;
    }

    /**
     * @return array
     */
    public function GetValues()
    {
        return $this->values;
    }
}