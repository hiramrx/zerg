<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 22:35
 */
namespace app\exception;

use app\ExceptionHandle as appHandle;
use think\facade\Log;
use think\Response;

class ExceptionHandle extends appHandle
{
    private $code;
    private $msg;
    private $errorCode;
    //还需要返回客户端当前请求的url

    public function render($request, \Throwable $e): Response
    {
        if ($e instanceof BaseException) {
            //如果是自定义的异常
            $this->code      = $e->code;
            $this->msg       = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {

            if (config('app_debug')){
                return parent::render($request, $e);
            } else {
                $this->code = 500;
                $this->msg  = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }

        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }

    public function recordErrorLog(\Throwable $e)
    {
//        Log::init([
//            'type' => 'file',
//            'path' => LOG_PATH,
//            'level' => ['error']
//        ]);
        Log::error($e->getMessage());
    }
}
