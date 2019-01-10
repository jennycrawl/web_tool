<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/wechat/pushMsgJson/', 'WechatApiController@pushMsgJson');
Route::post('/wechat/pushMsgExt/', 'WechatApiController@pushMsgExt');
Route::get('/wechat/getWxHis/', 'WechatApiController@getWxHis');
Route::get('/wechat/getWxPost/', 'WechatApiController@getWxPost');
Route::get('/wechat/getMsgList/', 'WechatApiController@getMsgList');

Route::get('/wechat/account/list', 'WechatAccountController@getAccountList');
Route::put('/wechat/account/{id}', 'WechatAccountController@updateAccount');
Route::delete('/wechat/account/{id}', 'WechatAccountController@deleteAccount');
Route::post('/wechat/account', 'WechatAccountController@insertAccount');
Route::get('/wechat/statistics', 'WechatStatisticsController@getStatistics');
Route::get('/wechat/msg', 'WechatMsgController@getList');

Route::get('/weibo/account/list', 'WeiboAccountController@getAccountList');
Route::put('/weibo/account/{id}', 'WeiboAccountController@updateAccount');
Route::delete('/weibo/account/{id}', 'WeiboAccountController@deleteAccount');
Route::post('/weibo/account', 'WeiboAccountController@insertAccount');
Route::get('/weibo/statistics', 'WeiboStatisticsController@getStatistics');
Route::get('/weibo/msg', 'WeiboMsgController@getList');
