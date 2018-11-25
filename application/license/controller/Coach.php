<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/2
 * Time: 11:23
 */

namespace app\license\controller;

use app\license\model\CoachInfo;
use think\Cache;
use app\license\service\Token as TokenService;
use think\Db;

class Coach
{
    public function coachRegister()
    {
//        $userInfo = Token::getUserInfo();
//        $openid = $userInfo['openid'];
//        $head_img = $userInfo['headimgurl'];
        $openid = 'ownsm0kupv4vmZ0CCWrhzphNcmm8';
        $head_img = 'url';
        $code = input('post.verifyCode');
        $tel = input('post.tel');
        $name = input('post.name');
        $verifyCode = Cache::get($tel);

        if ($verifyCode && $verifyCode == $code) {
            Db::startTrans();
            try {
                $coachInfo = new CoachInfo([
                    'name' => $name,
                    'tel' => $tel,
                    'openid' => $openid,
                    'head_img' => $head_img
                ]);
                $coachInfo->save();
                Db::commit();

                return json([
                    'code' => 200,
                    'msg' => 'OK'
                ]);
            } catch (\Exception $e) {
                Db::rollback();
                return json([
                   'code' => 500,
                   'msg' => '未知错误'
                ]);
            }
        }
    }

    public function updateCoachInfo()
    {
        $msgCode = input('post.msgCode');
        $name = input('post.name');
        $tel = input('post.tel');
        $openid = TokenService::getTokenValue('openid');

        if (Cache::get($tel) == $msgCode) {
            $coach = CoachInfo::get($openid);
            $coach->name = $name;
            $coach->tel = $tel;
            $coach->save();
        }
    }
}
