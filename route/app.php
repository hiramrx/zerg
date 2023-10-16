<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');

//banner
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

//theme
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');

//product
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id','api/:version.Product/getOne');

//category
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//token
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

//address
Route::get('api/:version/address','api/:version.Address/getAddress');
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

//order
Route::post('api/:version/order','api/:version.Order/placeOrder', [], ['id'=>'\d+']);
Route::get('api/:version/order/:id','api/:version.Order/getDetail');
Route::get('api/:version/order/by_user','api/:version.Order/getOrderByUid');

//user
Route::post('api/:version/user/wx_info','api/:version.User/updateUserInfo');
