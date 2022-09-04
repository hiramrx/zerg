<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/13
 * Time: 23:21
 */

namespace app\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|IdCheck'
    ];

    protected $message = [
        'ids' => 'id必须是以逗号分隔的正整数'
    ];

    protected function IdCheck($value)
    {
        $values = explode(',',$value);
        if(empty($values)){
            return false;
        }
        foreach ($values as $value){
            if (!$this->isPositiveInteger($value))
                return false;
        }
        return true;
    }
}