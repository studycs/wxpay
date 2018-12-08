<?php
namespace studycs\wxpay\sdk;
class WxPayNotify extends WxPayNotifyReply
{
	private $config = null;

    /**
     * @param $config
     * @param bool $needSign
     * @throws WxPayException
     */
	final public function Handle($config, $needSign = true)
	{
		$this->config = $config;
		$msg = "OK";
		$result = WxPayApi::notify($config,array($this,'NotifyCallBack'),$msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			$this->ReplyNotify(false);
			return;
		} else {
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		}
		$this->ReplyNotify($needSign);
	}

    /**
     * @param $objData
     * @param $config
     * @param $msg
     * @return bool
     */
	public function NotifyProcess($objData,$config,&$msg)
	{
		return false;
	}

    /**
     * @param $xmlData
     */
	public function LogAfterProcess($xmlData)
	{
		return;
	}

    /**
     * @param $data
     * @return bool
     */
	final public function NotifyCallBack($data)
	{
		$msg = "OK";
		$result = $this->NotifyProcess($data,$this->config,$msg);
		if($result == true){
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
		}
		return $result;
	}

    /**
     * @param bool $needSign
     * @throws WxPayException
     */
	final private function ReplyNotify($needSign = true)
	{
		if($needSign == true && $this->GetReturn_code() == "SUCCESS")
		{
			$this->SetSign($this->config);
		}
		$xml = $this->ToXml();
		$this->LogAfterProcess($xml);
		WxpayApi::replyNotify($xml);
	}
}