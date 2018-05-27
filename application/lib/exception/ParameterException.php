<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/12
 * Time: 16:02
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}