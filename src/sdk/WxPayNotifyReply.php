<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayNotifyReply
 * @package app\paySdk
 */
class WxPayNotifyReply extends  WxPayDataBaseSignMd5
{
    /**
     * @param $return_code
     */
    public function SetReturn_code($return_code)
    {
        $this->values['return_code'] = $return_code;
    }

    /**
     * @return mixed
     */
    public function GetReturn_code()
    {
        return $this->values['return_code'];
    }

    /**
     * @param $return_msg
     */
    public function SetReturn_msg($return_msg)
    {
        $this->values['return_msg'] = $return_msg;
    }

    /**
     * @return mixed
     */
    public function GetReturn_msg()
    {
        return $this->values['return_msg'];
    }

    /**
     * @param $key
     * @param $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }
}