<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/7/29
 * Time: 0:54
 */
namespace app\controller\v1;

use app\BaseController;

class User extends BaseController
{
    public function updateUserInfo()
    {
        $nickname = input('nickname');
        $extend = input('extend');
        $id = \app\service\Token::getCurrentUid();
        $userModel = \app\model\User::get($id);
        $userModel->save(['nickname' => $nickname, 'extend' => $extend]);
    }
}
