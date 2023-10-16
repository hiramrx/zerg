<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/13
 * Time: 23:14
 */

namespace app\controller\v1;


use app\exception\ParameterException;
use app\validate\IDCollection;
use app\model\Theme as ThemeModel;
use app\validate\IDMustBePositiveInt;
use app\exception\ThemeException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Theme
{
    /**
     * @param string $ids
     * @throws ThemeException
     * @throws ParameterException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException|DbException
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();

        $themes = explode(',', $ids);

        $result = ThemeModel::with(['topicImg', 'headImg'])->select($themes);
        if ($result->isEmpty()) {
            throw new ThemeException();
        }
        return json($result);
    }

    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $theme = ThemeModel::with(['products', 'topicImg', 'headImg'])->find($id);
        if (!$theme) {
            throw new ThemeException();
        }
        return $theme;
    }
}
