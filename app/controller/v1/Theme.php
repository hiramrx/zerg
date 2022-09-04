<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/13
 * Time: 23:14
 */

namespace app\controller\v1;


use app\validate\IDCollection;
use app\model\Theme as ThemeModel;
use app\validate\IDMustBePositiveInt;
use app\exception\ThemeException;

class Theme
{
    /**
     * @param string $ids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ThemeException
     * @throws \app\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();

        $themes = explode(',', $ids);

        $result = ThemeModel::with('topicImg,headImg')->select($themes);
        if ($result->isEmpty()) {
            throw new ThemeException();
        }
        return $result;
    }

    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $theme = ThemeModel::with('products,topicImg,headImg')->find($id);
        if (!$theme) {
            throw new ThemeException();
        }
        return $theme;
    }
}
