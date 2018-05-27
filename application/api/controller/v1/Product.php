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

    public function getAllInCategory($id= -1)
    {
        (new IDMustBePositiveInt())->goCheck();

        $products = ProductModel::getProductsByCategoryID($id,false);
        if(!$products){
            throw new ProductException();
        }

        return $products->hidden(['summary']);
    }

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