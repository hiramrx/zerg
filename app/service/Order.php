<?php
/**
 * Created by PhpStorm.
 * User: Horace
 * Date: 2018/1/17
 * Time: 13:57
 */

namespace app\service;

use app\model\Product as ProductModel;
use app\model\UserAddress;
use app\exception\UserException;
use app\model\Order as OrderModel;
use think\Db;
use think\Exception;
use app\model\OrderProduct;

class Order
{
    protected $products;
    protected $oProducts;
    protected $uid;

    public function place($uid, $oProducts)
    {
        //获取提交的订单数据
        $this->oProducts = $oProducts;
        $this->uid = $uid;
        $this->products = $this->getProductByOrder($oProducts);

        //查看库存量，返回状态，成功表示可以下单，失败返回false
        $status = $this->getOrderStatus();

        //如果库存不足，则表示不能创建订单，把订单号改成-1，代表失败。
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        //生成订单快照
        $snapshot = $this->OrderSnap($status);
        $order = $this->createOrder($snapshot);
        $order['pass'] = true;
        return $order;

    }

    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            $orderNo = self::generateOrderNo();//显示给页面的订单号
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['totalPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            Db::commit();

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }


    }

    public static function generateOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2018] .
            strtoupper(dechex(date('m'))) .
            date('d') . substr(time(), -5) .
            substr(microtime(), 2, 5) .
            sprintf('%02d', rand(0, 99));

        return $orderSn;
    }

    private function OrderSnap($status)
    {
        $orderSnap = [
            'snapName' => '',
            'snapImg' => '',
            'snapAddress' => '',
            'totalCount' => 0,
            'totalPrice' => 0,
            'pStatus' => []
        ];

        $orderSnap['snapName'] = $this->products[0]['name'];
        $orderSnap['snapImg'] = $this->products[0]['main_img_url'];
        $orderSnap['snapAddress'] = json_encode($this->getUserAddress());
        $orderSnap['totalCount'] = $status['totalCount'];
        $orderSnap['totalPrice'] = $status['orderPrice'];
        $orderSnap['pStatus'] = $status['orderInfo'];

        if (count($this->oProducts)) {
            $orderSnap['snapName'] .= '等';
        }

        return $orderSnap;
    }

    /**
     * @return array
     * @throws Exception
     * @throws UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getUserAddress()
    {
        $address = UserAddress::where('user_id', '=', $this->uid)
            ->find();
        if (!$address) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'code' => 80001
            ]);
        }

        return $address->toArray();
    }

    private function getOrderStatus()
    {
        //定义订单的状态
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'orderInfo' => [],
            'totalCount' => 0
        ];

        //把订单里的每一件商品拆分出来，单独求该商品的状态，并依据结果给status赋值
        foreach ($this->oProducts as $oProduct) {
            $pStatus =
                $this->getProductStatus(
                    $oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['price'];
            $status['totalCount'] += $oProduct['count'];
            array_push($status['orderInfo'], $pStatus);
        }
        return $status;
    }

    private function getProductStatus($oId, $oCount, $products)
    {
        //某个商品的状态
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'name' => '',
            'price' => 0,
            'main_img_url' => ''
        ];

        //根据商品的ID找到对应的记录，给pStatus赋值；
        for ($i = 0; $i < count($products); $i++) {
            if ($oId == $products[$i]['id']) {
                $pStatus['id'] = $products[$i]['id'];
                $pStatus['counts'] = $oCount;
                $pStatus['name'] = $products[$i]['name'];
                $pStatus['price'] = $products[$i]['price'] * $oCount;
                $pStatus['main_img_url'] = $products[$i]['main_img_url'];
                if ($products[$i]['stock'] - $oCount >= 0) {
                    $pStatus['haveStock'] = true;
                }
            }
        }

        return $pStatus;
    }

    private function getProductByOrder($products)
    {
        $oPIDs = [];
        foreach ($products as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        $product = ProductModel::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $product;
    }

}