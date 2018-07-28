<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/17
 * Time: 13:57
 */

namespace app\api\controller\v1;

use app\api\validate\PlaceOrder;
use app\api\service\Token;
use app\api\service\Order as OrderService;
use think\Controller;

class Order extends Controller
{
    public function placeOrder()
    {
        (new PlaceOrder())->goCheck();
        $products = input('post.products/a');
        $uid = Token::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }
}
