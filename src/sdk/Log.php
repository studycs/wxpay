<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 17:16
 */

namespace studycs\wxpay\sdk;


class Log
{
    /**
     * @var CLogFileHandler $handler
     */
    private $handler = null;
    /**
     * @var int $level
     */
    private $level = 15;

    /**
     * @var Log $instance
     */
    private static $instance = null;

    private function __construct(){}

    private function __clone(){}

    /**
     * @param null $handler
     * @param int $level
     * @return Log
     */
    public static function Init($handler = null,$level = 15)
    {
        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
            self::$instance->__setHandle($handler);
            self::$instance->__setLevel($level);
        }
        return self::$instance;
    }

    /**
     * @param $handler
     */
    private function __setHandle($handler){
        $this->handler = $handler;
    }

    /**
     * @param $level
     */
    private function __setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @param $msg
     */
    public static function DEBUG($msg)
    {
        self::$instance->write(1, $msg);
    }

    /**
     * @param $msg
     */
    public static function WARN($msg)
    {
        self::$instance->write(4, $msg);
    }

    /**
     * @param $msg
     */
    public static function ERROR($msg)
    {
        $debugInfo = debug_backtrace();
        $stack = "[";
        foreach($debugInfo as $key => $val){
            if(array_key_exists("file", $val)){
                $stack .= ",file:" . $val["file"];
            }
            if(array_key_exists("line", $val)){
                $stack .= ",line:" . $val["line"];
            }
            if(array_key_exists("function", $val)){
                $stack .= ",function:" . $val["function"];
            }
        }
        $stack .= "]";
        self::$instance->write(8, $stack . $msg);
    }

    /**
     * @param $msg
     */
    public static function INFO($msg)
    {
        self::$instance->write(2, $msg);
    }

    /**
     * @param $level
     * @return string
     */
    private function getLevelStr($level)
    {
        switch ($level)
        {
            case 1:
                return 'debug';
                break;
            case 2:
                return 'info';
                break;
            case 4:
                return 'warn';
                break;
            case 8:
                return 'error';
                break;
            default:
        }
        return null;
    }

    /**
     * @param $level
     * @param $msg
     */
    protected function write($level,$msg)
    {
        if(($level & $this->level) == $level )
        {
            $msg = '['.date('Y-m-d H:i:s').']['.$this->getLevelStr($level).'] '.$msg."\n";
            $this->handler->write($msg);
        }
    }
}