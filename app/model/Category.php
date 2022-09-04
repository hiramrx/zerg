<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 14:10
 */

namespace app\model;


class Category extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
}