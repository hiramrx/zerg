<?php

namespace app\model;

use think\Model;

class BaseModel extends Model
{
    protected $hidden = ['from','delete_time','update_time'];

    protected function prefixImgUrl($name,$data)
    {
        $finalUrl = $name;
        if($data['from'] == 1){
            return config('app.img_prefix').$name;
        } else {
            return $finalUrl;
        }
    }
}
