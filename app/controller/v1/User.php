<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/7/29
 * Time: 0:54
 */

namespace app\controller\v1;


use think\Controller;

class User extends Controller
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
