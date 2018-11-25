<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/16
 * Time: 1:50
 */

namespace app\license\controller;


use app\lib\exception\CommonException;

class Auth
{
    /*
     * 用户授权之后的回调接口，会携带一个code
     * 通过code换取用户信息并保存到数据库和session中
     * 如果请求没有携带code参数，session中没有openid
     * 跳转到授权页面，获取code
     * 将会设置用户信息存到服务器session中
     * */
    public function setUserInfo()
    {
        //获取当前页面url
        $current_url = $this->get_url();

        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $token = $this->getUserToken($code);
            $token = json_decode($token,true);
            if (array_key_exists('access_token',$token)) {
                //将用户信息存入session，以免对同一用户反复要求受权
                session('access_token',$token["access_token"]);
                session('refresh_token',$token["refresh_token"]);
                session('openid',$token["openid"]);
                session('scope',$token["scope"]);
                session('openidtime',time());
                //TODO:将用户的某些信息存入数据库
                return json([

                    'code' => 200,
                    'msg'  => 'OK'
                ]);
            } else {
                throw new CommonException([
                    'msg' => '请求token失败'
                ]);
            }
        } else {
            if (session('openidtime')) {
                $dtime = time()-session("openidtime");
                if ($dtime >= 7200) {
                    //授权过期，删除session中的信息
                    $refresh_token = session('refresh_token');
                    session('access_token',null);
                    session('refresh_token',null);
                    session('openid',null);
                    session('scope',null);
                    session('openidtime',null);
                    $token = $this->refreshToken($refresh_token);
                    $token = json_decode($token,true);
                    //授权信息更新完毕
                    session('access_token',$token["access_token"]);
                    session('refresh_token',$token["refresh_token"]);
                    session('openid',$token["openid"]);
                    session('scope',$token["scope"]);
                    session('openidtime',time());
                    return json([
                        'code' => 200,
                        'msg' => '更新openid完成'
                    ]);
                } else{
                    //TODO： session有用户信息，返回用户信息即可
                    return json([
                        'code' => 200,
                        'msg' => '用户正常访问'
                    ]);
                }
            } else {
                //session没有授权信息，进行OAuth2.0受权
                $this->wechatWebAuth($current_url);
            }
        }
    }

    private function wechatWebAuth($redirect_url = "")
    {
        $appid = config('token.appid');
        $redirect_url = $redirect_url === ""?config('token.redirect_url'):urlencode($redirect_url);
        $wxurl = sprintf(config('token.code_url'), $appid, $redirect_url);
        header('Location:'.$wxurl);
    }

    private function get_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    private function getUserToken($code)
    {
        $appid = config('token.appid');
        $appsecret = config('token.appsecret');
        $token_url = sprintf(config('token.token_url'), $appid, $appsecret, $code);
        $result = curl_get($token_url);
        if (!$result){
            throw new CommonException([
                'msg' => '请求token失败'
            ]);
        }
        return $result;
    }

    private function refreshToken($refresh_token)
    {
        if(empty($refresh_token)){
            return false;
        }
        $appid = config('token.appid');
        $refresh_url = sprintf(config('token.refresh_url'), $appid, $refresh_token);
        $token = curl_get($refresh_url);
        if (!$token) {
            throw new CommonException([
                'msg' => '刷新token失败'
            ]);
        }
        return $token;
    }
}