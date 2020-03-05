<?php
namespace studycs\wxpay\sdk;

/**
 * 只使用md5算法进行签名， 不管配置的是什么签名方式，都只支持md5签名方式
 * Class WxPayDataBaseSignMd5
 * @package studycs\wxpay\sdk
 */
class WxPayDataBaseSignMd5 extends WxPayDataBase
{
    /**
     * 生成签名 - 重写该方法
     * @param WxPayConfigInterface $config
     * @param bool $needSignType
     * @return string
     */
    public function MakeSign($config, $needSignType = false)
    {
        if($needSignType) {
            $this->SetSignType($config->GetSignType());
        }
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$config->GetKey();
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

}