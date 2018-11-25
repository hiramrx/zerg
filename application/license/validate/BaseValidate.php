<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/9
 * Time: 9:52
 */

namespace app\license\validate;

use think\Request;
use think\Validate;
use app\lib\exception;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $params = Request::instance()->param();
        $result = $this->batch()->check($params);
        if (!$result) {
            throw new exception\ParameterException();
        }
        return true;
    }
}