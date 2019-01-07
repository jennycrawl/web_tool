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
Route::get('/wechat/statistics', 'WechatStatisticsController@getStatistics');

Route::get('/weibo/account/list', 'WeiboAccountController@getAccountList');
Route::get('/weibo/statistics', 'WeiboStatisticsController@getStatistics');
