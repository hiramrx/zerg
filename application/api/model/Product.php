<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/13
 * Time: 23:13
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['create_time','pivot','update_time','delete_time','from'];

    public function getMainImgUrlAttr($name,$data)
    {
        return $this->prefixImgUrl($name,$data);
    }

    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public  static function getMostRecent($count)
    {
        return self::limit($count)->order('create_time','desc')->select();
    }

    public static function getProductsByCategoryID($categoryID, $paginate = true, $page = 1, $size = 30)
    {
        $query = self::
        where('category_id', '=', $categoryID);
        if (!$paginate)
        {
            return $query->select();
        }
        else
        {
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate(
                $size, true, [
                'page' => $page
            ]);
        }
    }

    public static function getProductDetail($id)
    {
        return self::with('imgs,properties')->find($id);
    }
}