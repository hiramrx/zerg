<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 21:43
 */

namespace app\controller\v1;


use app\service\UserToken;
use app\validate\TokenGet;

class Token
{
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $usertoken = new UserToken($code);
        $token = $usertoken->get();
        return [
            'token' => $token
        ];
    }
}