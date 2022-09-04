<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/8/19
 * Time: 21:54
 */

namespace app\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg  = '请求的订单数据不存在';
    public $errorCode = 80000;
}
