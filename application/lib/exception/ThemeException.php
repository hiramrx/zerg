<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/14
 * Time: 22:41
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '请求的主题不存在';
    public $errorCode = 30000;
}