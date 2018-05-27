<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 15:10
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 50000;
    public $msg         = '指定的类别不存在，请检查参数';
}
