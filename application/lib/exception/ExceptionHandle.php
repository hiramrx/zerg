<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 22:35
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandle extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //还需要返回客户端当前请求的url

    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
            //如果是自定义的异常
            $this->code      = $e->code;
            $this->msg       = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {

            if (config('app_debug')){
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg  = '服务器内部错误';
                $this->errorCode = 999;
                $this->RecordErrorLog($e);
            }
        }

        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }

    public function RecordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}