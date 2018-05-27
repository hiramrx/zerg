<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/10
 * Time: 17:11
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;


class Banner
{
    public function getBanner($id)
    {
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();

        $banner = BannerModel::getBannerByID($id);
        if(!$banner){
            throw new BannerMissException();
        }
        return $banner;
    }
}
