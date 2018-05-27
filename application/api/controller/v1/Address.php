<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/2/3
 * Time: 13:06
 */

namespace app\api\controller\v1;


use app\api\model\User;
use app\api\validate\AddressNew;
use app\api\service\Token;
use think\Controller;

class Address extends Controller
{
    //前置方法作为键名。需要前置方法的方法作为值
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    protected function checkPrimaryScope()
    {

    }

    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();

        //从token中获取uid，根据uid查找用户数据，判断用户是否存在
        $uid = Token::getCurrentUid();
        $user = User::get($uid);
        if(!$user){
            throw new UserException();
        }

        //获取从客户端提交的地址信息
        $dataArray = $validate->getDataByRule(input('post.')); //伪代码

        //根据用户地址信息是否存在，判断是添加还是更新地址
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->sava($dataArray);
        }
        return 'success';
    }
}
