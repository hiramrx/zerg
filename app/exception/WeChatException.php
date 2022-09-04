<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 23:28
 */

namespace app\exception;


class WeChatException extends BaseException
{
    public $code        = 400;
    public $errorCode   = 999;
    public $msg         = '微信服务器接口调用失败';
}
