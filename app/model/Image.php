<?php

namespace app\model;

class Image extends BaseModel
{
    protected $hidden = ['id','from','delete_time','update_time'];

    public function getUrlAttr($name,$data)
    {
        return parent::prefixImgUrl($name,$data);
    }
}
