<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/16
 * Time: 22:39
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.appid');
        $this->wxAppSecret = config('wx.appsecret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,
            $this->wxAppSecret, $this->code);
    }

    /**
     * @return string
     * @throws Exception
     * @throws WeChatException
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);

        $wxResult = json_decode($result,true);
        if (empty($wxResult)) {
            throw new Exception('获取token失败，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * @param $wxResult
     * @return string
     * @throws TokenException
     */
    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenId($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);

        $token = $this->saveToCache($cachedValue);
        return $token;

    }

    /**
     * @param $cachedValue
     * @return string
     * @throws TokenException
     */
    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');

        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorcode' => $wxResult['errcode']
        ]);
    }
}
