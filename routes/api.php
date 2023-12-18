<?php

use App\Http\Controllers\WechatAccountController;
use App\Http\Controllers\WechatApiController;
use App\Http\Controllers\WechatMsgController;
use App\Http\Controllers\WechatStatisticsController;
use App\Http\Controllers\WeiboAccountController;
use App\Http\Controllers\WeiboMsgController;
use App\Http\Controllers\WeiboStatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/wechat/pushMsgJson/', [WechatApiController::class, 'pushMsgJson']);
//Route::post('/wechat/pushMsgExt/', [WechatApiController::class, '@pushMsgExt']);
//Route::get('/wechat/getWxHis/', [WechatApiController::class, 'getWxHis']);
//Route::get('/wechat/getWxPost/', [WechatApiController::class, 'getWxPost']);
//Route::get('/wechat/getMsgList/', [WechatApiController::class, 'getMsgList']);

//Route::get('/wechat/account/list', [WechatAccountController::class, 'getAccountList']);
//Route::put('/wechat/account/{id}', [WechatAccountController::class, 'updateAccount']);
//Route::delete('/wechat/account/{id}', [WechatAccountController::class, 'deleteAccount']);
//Route::post('/wechat/account', [WechatAccountController::class, 'insertAccount']);
//Route::get('/wechat/statistics', [WechatStatisticsController::class, 'getStatistics']);
//Route::get('/wechat/msg', [WechatMsgController::class, 'getList']);

Route::prefix('/wechat')->group(function () {
    Route::prefix('/account')->group(function () {
        Route::get('list', [WechatAccountController::class, 'getAccountList']);
        Route::put('/{id}', [WechatAccountController::class, 'updateAccount']);
        Route::delete('/{id}', [WechatAccountController::class, 'deleteAccount']);
        Route::post('/', [WechatAccountController::class, 'insertAccount']);
    });
    Route::post('/pushMsgJson/', [WechatApiController::class, 'pushMsgJson']);
    Route::post('/pushMsgExt/', [WechatApiController::class, '@pushMsgExt']);
    Route::get('/getWxHis/', [WechatApiController::class, 'getWxHis']);
    Route::get('/getWxPost/', [WechatApiController::class, 'getWxPost']);
    Route::get('/getMsgList/', [WechatApiController::class, 'getMsgList']);
    Route::get('/wechat/statistics', [WechatStatisticsController::class, 'getStatistics']);
    Route::get('/wechat/msg', [WechatMsgController::class, 'getList']);
});

Route::prefix('/weibo')->group(function () {
    Route::prefix('/account')->group(function () {
        Route::get('/list', [WeiboAccountController::class, 'getAccountList']);
        Route::put('/{id}', [WeiboAccountController::class, 'updateAccount']);
        Route::delete('/{id}', [WeiboAccountController::class, 'deleteAccount']);
        Route::post('/', [WeiboAccountController::class, 'insertAccount']);
    });
    Route::get('/statistics', [WeiboStatisticsController::class, 'getStatistics']);
    Route::get('/msg', [WeiboMsgController::class, 'getList']);
});
