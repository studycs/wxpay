<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 17:15
 */

namespace studycs\wxpay\sdk;


Interface ILogHandler
{
    public function write($msg);
}