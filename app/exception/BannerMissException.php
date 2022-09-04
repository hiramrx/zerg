<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 22:46
 */

namespace app\exception;


class BannerMissException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 40000;
    public $msg         = '请求的Banner不存在';
}
