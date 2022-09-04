<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/2/2
 * Time: 22:17
 */

namespace app\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','product_id','delete_time'];

    public function imgUrl()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}