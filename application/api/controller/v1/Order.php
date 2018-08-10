<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/17
 * Time: 13:57
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PlaceOrder;
use app\api\service\Token;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use think\Controller;
use think\Request;

class Order extends Controller
{
    /**
     * @return array
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function placeOrder()
    {
        (new PlaceOrder())->goCheck();
        $products = input('post.products/a');
        $uid = Token::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }

    /**
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function getOrderByUid($page)
    {
        $uid = Token::getCurrentUid();
        $order = OrderModel::getByUid($uid,$page)->toArray();
        return [
            'current_page' => $page,
            'data' => $order
        ];
    }

    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail)
        {
            throw new \Exception('未找到该订单');
        }
        return $orderDetail->hidden(['prepay_id']);
    }
}
