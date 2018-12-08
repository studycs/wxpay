<?php
namespace studycs\wxpay\helper;


class Html extends \yii\helpers\Html
{
    /**
     * @return string
     */
    public static function getOrder(){
        $num   = self::num2alpha(intval(date('Y'))-intval(date('Y',0)));
        $sn    = $num.strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).str_pad(sprintf('%02d',rand(0,999)),3,rand(1,9));
        return $sn;
    }

    /**
     * @param $n
     * @return string
     */
    public static function num2alpha($n)
    {
        $n = $n - 1;
        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
            $r  = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
            $n -= pow(26,$i);
        }
        return $r;
    }

    /**
     * @return string
     */
    public static function getKey(){
        return \yii::$app->params['wxpay']['key'];
    }

    /**
     * @return string
     */
    public static function getCertPath(){
        return \yii::$app->params['wxpay']['sslCert'];
    }

    /**
     * @return string
     */
    public static function getKeyPath(){
        return \yii::$app->params['wxpay']['sslKey'];
    }

    /**
     * @return string
     */
    public static function getMchId(){
        return \yii::$app->params['wxpay']['mchId'];
    }

    /**
     * @return string
     */
    public static function getAppId(){
        return \yii::$app->params['wxpay']['appId'];
    }

    /**
     * @return string
     */
    public static function getPayUrl(){
        return \yii::$app->params['wxpay']['payUrl'];
    }

    /**
     * @return string
     */
    public static function getBankPay(){
        return \yii::$app->params['wxpay']['bankPay'];
    }

    /**
     * @return string
     */
    public static function getPkUrl(){
        return \yii::$app->params['wxpay']['pkUrl'];
    }

    /**
     * @return string
     */
    public static function getSePayUrl(){
        return \yii::$app->params['wxpay']['sePayUrl'];
    }

    /**
     * @return string
     */
    public static function getAppSecret(){
        return \yii::$app->params['wxpay']['appSecret'];
    }


}