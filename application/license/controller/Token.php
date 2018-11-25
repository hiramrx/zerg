<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/2
 * Time: 14:24
 */

namespace app\license\controller;


use think\Cache;
use app\license\service\Token as TokenService;
use think\Request;

class Token
{
    public function getUserToken()
    {
        $code = input('get.code');
        $userToken = new TokenService($code);
        $res = $userToken->getUserToken();
        $res = json_decode($res, true);

        if (array_key_exists('access_token', $res)) {
            $token = $res['access_token'];
            $res = array_shift($res);
            Cache::set($token, $res);
            return json([
                'code' => 200,
                'token' => $token
            ]);
        } else {
            return json([
                'code' => $res['errcode'],
                'msg' => $res['errmsg']
            ]);
        }
    }

    public static function getUserTokenFromCache()
{
    $userToken = Request::instance()->header('token');
    if (!$userToken) {
        header('location:login');
    }
    return $userToken;
}

    public static function getUserOpenid()
    {
        $userToken = self::getUserTokenFromCache();
        $openid = (Cache::get($userToken))['openid'];
        return $openid;
    }

    public static function getUserInfo()
    {
        $userToken = self::getUserTokenFromCache();
        $openid = self::getUserOpenid();
        $user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $userToken . "&openid=" . $openid . "&lang=zh_CN";
        $user_info = json_decode(curl_get($user_info_url));
        if (array_key_exists('openid', $user_info)) {
            return json([
                'code' => 200,
                'user_info' => $user_info
            ]);
        } else {
            return json([
                'code' => $user_info['errcode'],
                'msg' => $user_info['errmsg']
            ]);
        }
    }

    public function getCode()
    {
        //读取配置
        $appid = config('token.appid');
        $redirect_url = urlencode(config('token.redirect_url'));
        $url = sprintf(config('token.code_url'), $appid, $redirect_url);
        header("location:" . $url);
    }
}
