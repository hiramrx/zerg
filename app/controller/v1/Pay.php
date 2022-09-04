<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 2018/4/14
 * Time: 22:09
 */

namespace app\controller\v1;

use app\model\Order as OrderModel;

class Pay
{
    public function getPreOrder()
    {
        $preData = [];
        $orderID = input(post . orderID);
        $order = new OrderModel();
        $order->get($orderID);
        $appId = config(wx . appid);
        $nonceStr = getRandChar(32);
        $signType = 'MD5';
        $timeStamp = $order->create_time;
        $package = $order->order_no;
    }

}