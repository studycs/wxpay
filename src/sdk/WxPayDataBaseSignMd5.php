<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayDataBaseSignMd5
 * @package app\paySdk
 */
class WxPayDataBaseSignMd5 extends WxPayDataBase
{
    /**
     * @param $config
     * @param bool $needSignType
     * @return string
     */
    public function MakeSign($config, $needSignType = false)
    {
        if($needSignType) {
            $this->SetSignType($config->GetSignType());
        }
        ksort($this->values);
        $string = $this->ToUrlParams();
        $string = $string . "&key=".$config->GetKey();
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }
}