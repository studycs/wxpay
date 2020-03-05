<?php
namespace studycs\wxpay\example;
/**
 * Class CLogFileHandler
 * @package studycs\wxpay\example
 */
class CLogFileHandler
{
    /** @var resource $handle  */
    private $handle = null;

    public function __construct($file = '')
    {
        $this->handle = fopen($file,'a');
    }

    public function write($msg)
    {
        fwrite($this->handle, $msg, 4096);
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}