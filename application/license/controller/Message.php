<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/4/28
 * Time: 22:54
 */

namespace app\license\controller;

use app\license\service\Verify;

class Message
{
    public function getMsgCode()
    {
        $tel = input('get.tel');
        $result = json_decode(Verify::sendCode($tel),true);
        if ($result) {
            return json([
                'code' => $result['result'],
                'msg' => $result['errmsg'],
                'sid' => $result['sid']
            ]);
        }
    }
}
