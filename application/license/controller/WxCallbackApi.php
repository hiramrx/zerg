<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/5/9
 * Time: 23:30
 */

namespace app\license\controller;


use app\license\model\CoachInfo;
use app\license\model\StudentInfo;
use think\Db;

class WxCallbackApi
{
    public function responseMsg()
    {
        //获取post提交的数据，不能使用$_POST接收，因为不能识别xml数据
        $postStr = file_get_contents('php://input');
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = $postObj->MsgType;

            switch ($RX_TYPE) {
                case 'text':
                    $resultStr = $this->handleText($postObj);
                    break;
                case 'event':
                    $resultStr = $this->handleEvent($postObj);
                    break;
                default:
                    $resultStr = 'Unknow msg type: ' . $RX_TYPE;
                    break;
            }
            return $resultStr;
        } else {
            echo 'success';
            exit;
        }
    }

    public function handleText($postObj)
    {
        $keyword = trim($postObj->Content);
        if (!empty($keyword)) {
            $contentStr = "Welcome to wechat world!";
            $resultStr = $this->responseText($postObj, $contentStr);
            return $resultStr;
        } else {
            return "Input something...";
        }
    }

    public function handleEvent($object)
    {
        $contentStr = '';
        switch ($object->Event) {
            case 'subscribe':
                $contentStr = $this->handleSubscribe($object);
                break;
            case 'SCAN':
                $contentStr = $this->handleScan($object);
                break;
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }

    private function handleSubscribe($object)
    {
        $str = '';
        if (isset($object->EventKey) || isset($object->ticket)) {
            $eventKey = $object->EventKey;
            $eventKey = substr($eventKey, 8);
            Db::startTrans();
            try {
                //            $userInfo = Token::getUserInfo();
                $stu = new StudentInfo([
//                'openid' => $userInfo['openid'],
                    'openid' => 'ownsm0kupv4vmZ0CCWrhzphNcmm8',
                    'coach_id' => $eventKey,
//                'head_img' => $userInfo['headimgurl']
                    'head_img' => 'img'
                ]);
                $stu->save();
                $coach = CoachInfo::get($eventKey);
                $name = $coach->name;
                $tel = $coach->tel;
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
            }
            $str = '恭喜！您已成为' . $name . '的学员，号码是' . $tel . '，点击‘我的’完善资料';
        } else {
            $str = '感谢您关注学员练车，赶快点击我的完善资料吧';
        }
        return $str;
    }

    private function handleScan($object)
    {
        $str = '';
        $eventKey = $object->EventKey;
        if ($eventKey == 1) {
            $str = "欢迎关注驾校教练必备的学员练车神器！马上点击‘我的’完善教练资料吧！";
        } else {
            Db::startTrans();
            try {
                //            $userInfo = Token::getUserInfo();
                $stu = new StudentInfo([
//                'open_id' => $userInfo['openid'],
                    'openid' => 'ownsm0kupv4vmZ0CCWrhzphNcmm8',
                    'coach_id' => $eventKey,
//                'head_img' => $userInfo['headimgurl']
                    'head_img' => 'img-url'
                ]);
                $stu->save();
                $coach = CoachInfo::get($eventKey);
                $name = $coach->name;
                $tel = $coach->tel;
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
            }

            $str = '恭喜！您已成为' . $name . '的学员，号码是' . $tel . '，点击‘我的’完善资料';
        }
        return $str;
    }

    private function responseText($object, $content)
    {
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $resultStr;
    }

    public function isValid()
    {
        $echoStr = $_GET['echostr'];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'TOKEN';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
