<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/2
 * Time: 11:09
 */

namespace app\license\service;

use Qcloud\Sms\SmsSingleSender;
use think\Cache;


class Verify
{
    public static function sendCode($tel)
    {
        //获取一个4位数的随机数字
        $code = getRandNum(4);
        try {
            //读取配置
            $appid = config('msg.appid');
            $appkey = config('msg.appkey');

            //发送验证码
            $sender = new SmsSingleSender($appid, $appkey);
            $result = $sender->send(0, "86", $tel,
                "$code 为您的登录验证码，请于5分钟内填写。如非本人操作，请忽略本短信。", "", "");

            //如果发送成功则将验证码存入缓存，过期时间5分钟
            Cache::set($tel, $code, 300);
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}