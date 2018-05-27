<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 12:55
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    protected $message = [
        'id' => 'id必须是正整数'
    ];

    public function goCheck()
    {
        $params   = Request::instance()->param();

        $result  = $this->batch()->check($params);
        if(!$result){
            throw new ParameterException([
                'msg' => $this->error
            ]);
        }else{
            return true;
        }
    }

    /**
     * 验证函数需要传入的参数
     * @param $value验证数据
     * @param string $rule验证规则
     * @param string $data全部数据（数组）
     * @param string $field字段名
     * @return bool
     */

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if(is_numeric($value)&&is_int($value+0)&&($value+0)>0){
            return true;
        }else{
            return false;
        }
    }

    protected function isNotEmpty($value,$rule = '',$data = '',$field = '')
    {
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    public function getDataByRule($array)
    {
        $newArray = [];
        foreach ($this->rule as $key=>$value) {
            $newArray[$key] = $array[$key];
        }
        return $newArray;
    }
}
