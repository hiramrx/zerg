<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/3
 * Time: 10:43
 */

namespace app\license\model;


use think\Model;

class CoachInfo extends Model
{
    public static function getKeyByOpenid($openid)
    {
        $result = self::where('openid','=',$openid)->find();
        return $result->id;
    }
    //TODO: 教练表需要重新设计
}