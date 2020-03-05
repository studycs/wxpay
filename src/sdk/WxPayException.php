<?php


namespace studycs\wxpay\sdk;


class WxPayException extends \Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}