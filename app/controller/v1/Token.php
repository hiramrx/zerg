<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 21:43
 */

namespace app\controller\v1;


use app\exception\BaseException;
use app\service\UserToken;
use app\validate\TokenGet;

class Token
{
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $usertoken = new UserToken($code);
        $token = $usertoken->get();
        return json([
            'token' => $token
        ]);
    }

    public function verifyToken($token='')
    {
        if(!$token){
            throw new BaseException([
                'token不允许为空'
            ]);
        }
        $valid = \app\service\Token::verifyToken($token);
        return json([
            'isValid' => $valid
        ]);
    }
}