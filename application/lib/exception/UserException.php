<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/2/4
 * Time: 14:42
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 50000;
    public $msg         = '用户不存再，请检查参数';
}