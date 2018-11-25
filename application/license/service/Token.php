<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/3
 * Time: 9:15
 */

namespace app\license\service;

use app\lib\exception\CommonException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Request;

class Token
{
    protected $appid;
    protected $appsecret;
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appid = config('token.appid');
        $this->appsecret = config('token.appsecret');
    }

    public function getUserToken()
    {
        $token_url = sprintf(config('token.token_url'),$this->appid,$this->appsecret,$this->code);
        $result = curl_get($token_url);
        if (!$result){
            throw new CommonException([
                'msg' => '请求token失败'
            ]);
        }
        return $result;
    }

    public function refreshToken($refresh_token)
    {
        if(empty($refresh_token)){
            return false;
        }
        $refresh_url = sprintf(config('token.refresh_url'), $this->appid, $refresh_token);
        $token = curl_get($refresh_url);
        if (!$token) {
            throw new CommonException([
                'msg' => '刷新token失败'
            ]);
        }
        return $token;
    }

    public static function getTokenValue($key)
    {
        $token = Request::instance()->header('token');
        $values = Cache::get($token);
        if(!$values){
            throw new TokenException();
        }else{
            if(!is_array($values)){
                $values = json_decode($values,true);
            }
            if(array_key_exists($key,$values)){
                return $values[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    public function getWxAccessToken()
    {
        $wxAccessUrl = sprintf(config('token.wx_access_url'), $this->appid, $this->appsecret);
        $result = curl_get($wxAccessUrl);
        if (!$result) {
            throw new Exception();
        }
        return $result['access_token'];
    }
}