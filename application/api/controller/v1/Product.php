<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/15
 * Time: 22:32
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;


class Product
{
    /**
     * @param int $count
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecent($count=15)
    {
        (new Count())->goCheck();

//        $product = productModel::getMostRecent($count);
        $product = ProductModel::limit($count)->order('create_time','desc')->select();
        if($product->isEmpty()){
            throw new ProductException();
        }
        return $product;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     */
    public function getAllInCategory($id= -1)
    {
        (new IDMustBePositiveInt())->goCheck();

        $products = ProductModel::getProductsByCategoryID($id,false);
        if(!$products){
            throw new ProductException();
        }

        return $products->hidden(['summary']);
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws ProductException
     * @throws \app\lib\exception\ParameterException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $details = ProductModel::getProductDetail($id);
        if(!$details){
            throw new ProductException();
        }
        return $details;
    }
}