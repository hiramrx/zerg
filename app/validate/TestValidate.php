<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/10
 * Time: 20:35
 */

namespace app\validate;


use think\Validate;

class TestValidate extends Validate
{
    protected $rule = [
      'id' => 'alpha'
    ];
}