<?php
/**
 * Created by PhpStorm.
 * User: SUMSUNG
 * Date: 2018/4/2
 * Time: 16:54
 */

namespace app\validate;

use app\exception\ParameterException;

class PlaceOrder extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    public function checkProducts($values)
    {
        if (empty($values)) {
            throw new ParameterException([
                'msg' => '订单数据异常'
            ]);
        }

//        if (！is_array($values)) {
//            throw new ParameterException([
//                'msg' => '商品数据异常'
//            ]);
//        }

        foreach ($values as $value) {
            $this->checkProduct($value);
        }

        return true;
    }

    protected function checkProduct($value)
    {
        $validate = new BaseValidate([
            'ProductId' => 'require|isPositiveInteger',
            'count' => 'require|isPositiveInteger'
        ]);
        $validate->check($value);
    }
}