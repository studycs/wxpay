<?php
namespace studycs\wxpay\config;
use studycs\wxpay\helper\Html;
/**
 * Class WxComPay
 * @package app\paySdk
 */
class WxComPay extends Base
{
    /**
     * @var array $params
     */
    private $params = [];
    const PAYURL   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    const SEPAYURL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";
    const PKURL    = "https://fraud.mch.weixin.qq.com/risk/getpublickey";
    const BANKPAY  = "https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank";

    /**
     * @return mixed|string
     */
    public function getPuyKey(){
        $this->params = ['mch_id'=> self::MCHID,'nonce_str'=>md5(time()),'sign_type'=>'MD5'];
        return $this->send(self::PKURL);
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function comPay($data){
        $this->params = [
            'mch_appid'       =>self::APPID,
            'mchid'           =>self::MCHID,
            'nonce_str'       =>md5(time()),
            'partner_trade_no'=>Html::getOrder(),
            'openid'          =>$data['openid'],
            'check_name'      =>'NO_CHECK',
            'amount'          =>$data['price'],
            'desc'            =>'下级代理返现提现到零钱！',
            'spbill_create_ip'=>$_SERVER['SERVER_ADDR'],
        ];
        return $this->send(self::PAYURL);
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function bankPay($data){
        $this->params = [
            'mch_id'          => self::MCHID,
            'partner_trade_no'=> date('YmdHis'),//商户付款单号
            'nonce_str'       => md5(time()), //随机串
            'enc_bank_no'     => $data['enc_bank_no'],//收款方银行卡号RSA加密
            'enc_true_name'   => $data['enc_true_name'],//收款方姓名RSA加密
            'bank_code'       => $data['bank_code'],//收款方开户行
            'amount'          => $data['amount'],//付款金额
        ];
        return $this->send(self::BANKPAY);
    }

    /**
     * @param $oid
     * @return mixed|string
     */
    public function searchPay($oid){
        $this->params = [
            'nonce_str'  => md5(time()),//随机串
            'partner_trade_no'  => $oid, //商户订单号
            'mch_id'  => self::MCHID,//商户号
            'appid'  => self::APPID //APPID
        ];
        return $this->send(self::SEPAYURL);
    }

    /**
     * @return array
     */
    public function sign(){
        return $this->setSign($this->params);
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function send($url){
        $res  = $this->sign();
        $xml  = $this->ArrToXml($res);
        $returnData = $this->postData($url,$xml);
        $data = $this->XmlToArr($returnData);
        return $data;
    }
}
