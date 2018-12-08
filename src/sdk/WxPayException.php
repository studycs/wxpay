<?php
namespace studycs\wxpay\sdk;
/**
 * Class WxPayException
 * @package app\paySdk
 */
class WxPayException extends \Exception {
    /**
     * @return string
     */
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
