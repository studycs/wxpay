<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/8
 * Time: 18:08
 */

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


}