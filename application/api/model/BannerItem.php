<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id','delete_time','update_time'];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}
