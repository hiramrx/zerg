<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/15
 * Time: 22:51
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];
}