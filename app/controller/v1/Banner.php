<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/10
 * Time: 17:11
 */

namespace app\controller\v1;

use app\validate\IDMustBePositiveInt;
use app\model\Banner as BannerModel;
use app\exception\BannerMissException;


class Banner
{
    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws BannerMissException
     * @throws \app\exception\ParameterException
     */
    public function getBanner($id)
    {
//        $validate = new IDMustBePositiveInt();
//        $validate->goCheck();

        $banner = BannerModel::getBannerByID($id);
//        if(!$banner){
//            throw new BannerMissException();
//        }
        return $banner;
    }
}
