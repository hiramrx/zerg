<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/9
 * Time: 10:11
 */

namespace app\license\validate;


class IsMobile extends BaseValidate
{
    protected $rule = [
        'phone_number' => 'isMobile'
    ];

    protected $message = [
        'phone_number' => '请输入正确的手机号码'
    ];

    protected function isMobile($value)
    {
        $result = preg_match('/1(3,4,5,7,8)[0-9]{9}/',$value);
        if (!$result) {
            return false;
        }
        return true;
    }
}