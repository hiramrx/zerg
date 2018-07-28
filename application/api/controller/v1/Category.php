<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 14:09
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /**
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::with('img')->select();
        if ($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}