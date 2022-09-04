<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/11
 * Time: 12:55
 */

namespace app\validate;

use app\exception\ParameterException;
use think\facade\Request;
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
     * @param mixed $value 验证数据
     * @param string $rule 验证规则
     * @param string $data 全部数据（数组）
     * @param string $field 字段名
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

    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
