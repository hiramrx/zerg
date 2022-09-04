<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/17
 * Time: 14:59
 */

namespace app\exception;


class TokenException extends BaseException
{
    public $code        = 401;
    public $errorCode   = 10001;
    public $msg         = 'Token已过期或无效Token';
}