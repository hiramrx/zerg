<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/3
 * Time: 9:42
 */

return [
    'appid'         => 'appid',
    'appsecret'     => 'appsecret',
    'redirect_url'  => 'http://car.phpbing.com/api/setuserinfo',
    'code_url'      => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect',
    'token_url'     => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',
    'qr_code_url'   => 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s',
    'wx_access_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    "refresh_url"   => 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s'
];
