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

    /**
     * @param $count
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public  static function getMostRecent($count)
    {
        return self::limit($count)->order('create_time','desc')->select();
    }

    /**
     * @param $categoryID
     * @param bool $paginate
     * @param int $page
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getProductsByCategoryID($categoryID, $paginate = true, $page = 1, $size = 30)
    {
        $query = self::where('category_id', '=', $categoryID);
        if (!$paginate) {
            return $query->select();
        }
        else {
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate($size, true, ['page' => $page]);
        }
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getProductDetail($id)
    {
        return self::with('imgs,properties')->find($id);
    }
}