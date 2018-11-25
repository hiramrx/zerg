<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/14
 * Time: 9:07
 */

namespace app\license\controller;

use app\lib\exception\ParameterException;
use app\lib\exception\TicketException;
use app\license\model\CoachInfo;
use app\license\service\Token as TokenService;

class QrCode
{
    public function qrCode()
    {
        $identity = input('get.identity');
        if (!is_numeric($identity)) {
            throw new ParameterException([
                'msg' => '参数必须是数字'
            ]);
        }

        //$openid = TokenService::getTokenValue('openid');
        $openid = 'ownsm0kupv4vmZ0CCWrhzphNcmm8';
        $scene_id = CoachInfo::getKeyByOpenid($openid);

        //接口调用凭证，并非用户登陆的token
        //$access_token = TokenService::getWxAccessToken();
        $access_token = '9_ctPLz_UtJphaY0apzvMQ3SIihEIb07aNjyQmNdJRVg9HUOyAur5aVUkmPjidUttlujl8tCaNRFSF8aytck9EFHXvQiBbR1_jPbrnpl6cesFIV0MIp7-wlt_E9iTwvSvmfExFKKKLX-qgXkp8LTDcAJADLV';

        //获取二维码ticket
        $qrCodeUrl = sprintf(config('token.qr_code_url'), $access_token);
        $dataArray = [
            'action_name' => 'QR_LIMIT_SCENE',
            'action_info' => ['scene' => ['scene_id' => $scene_id]]
        ];

        //根据身份生成不同的二维码
        switch ($identity) {
            case 1:
                $result = $this->generateQrCode($qrCodeUrl, $dataArray, $identity=1);
                break;
            case 2:
                $result = $this->generateQrCode($qrCodeUrl, $dataArray, $identity=2);
                break;
            default :
                throw new ParameterException([
                    'msg' => '未知身份'
                ]);
        }
        return $result;
    }

    private function generateQrCode($qrCodeUrl, $dataArray, $identity)
    {
        if ($identity == 1) {
            $dataArray = [
                'action_name' => 'QR_LIMIT_SCENE',
                'action_info' => ['scene' => ['scene_id' => 1]]
            ];
        }

        $ticket_result = curl_post($qrCodeUrl,json_encode($dataArray));
        $ticket = json_decode($ticket_result, true);
        if (!array_key_exists('ticket',$ticket)) {
            throw new TicketException();
        }
        $ticket = $ticket['ticket'];
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
        return json([
            'code' => 0,
            'msg' => $url
        ]);
    }
}
