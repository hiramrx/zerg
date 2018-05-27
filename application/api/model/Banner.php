<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 14:21
 */

namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];

    public function items()
    {
        return $this->hasMany('Banner_Item','banner_id','id');
    }

    public static function getBannerByID($id)
    {
        return self::with(['items','items.img'])->find($id);
    }
}
