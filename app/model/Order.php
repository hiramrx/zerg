<?php
/**
 * Created by Hiram.
 * User: Hiram
 * Date: 2018/4/5
 * Time: 22:30
 */

namespace app\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time'];


    public static function getByUid($uid, $page)
    {
        return self::where(['user_id' => $uid])->page($page)->order('create_time desc')->select();
    }

    public function getSnapItemsAttr($value)
    {
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode(($value));
    }

    public function products()
    {
        return $this->belongsToMany('Product', 'order_product', 'product_id', 'order_id');
    }
}
