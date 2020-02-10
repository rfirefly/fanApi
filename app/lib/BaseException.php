<?php
namespace app\lib;

/**
 * 基础异常
 */
class BaseException extends \RuntimeException
{
    public $code = 400;
    public $message = "发生错误";
    public $errorCode = 9999;

    public function __construct($params = [])
    {
        if(!is_array($params)) return;
        if(array_key_exists('code', $params)) $this->code = $params['code'];
        if(array_key_exists('message', $params)) $this->message = $params['message'];
        if(array_key_exists('errorCode', $params)) $this->errorCode = $params['errorCode'];
    }
}
