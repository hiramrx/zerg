<?php
/**
 * Created by Hiram.
 * User: Hiram
 * Date: 2018/4/5
 * Time: 22:30
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'create_time', 'delete_time'];

    public static function getByUid($uid, $page)
    {
        return self::where(['user_id' => $uid])->page($page)->select();
    }
}
