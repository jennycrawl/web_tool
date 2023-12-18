<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WechatAccount as WechatAccountTable;
use App\Models\WechatMsg as WechatMsgTable;

class WechatStatisticsController extends Controller
{
    //
    public function getStatistics(Request $request)
    {
        $accountId = intval($request->query('account_id','0'));
        $startDate = $request->query('start_date','');
        $endDate = $request->query('end_date','');
        $startDate && $startDate = date("Y-m-d H:i:s",strtotime($startDate));
        $endDate && $endDate = date("Y-m-d H:i:s",strtotime($endDate) + 86400);
        $selectColmun = "wechat_account.name," .
            "wechat_account.id as id," .
            "wechat_account.crawl_time as crawl_time," .
            "count(wechat_msg.id) as count," .
            "sum(wechat_msg.read) as read_sum," .
            "sum(wechat_msg.like) as like_sum," .
            "avg(wechat_msg.read) as read_avg," .
            "avg(wechat_msg.like) as like_avg," .
            "max(wechat_msg.read) as read_max," .
            "max(wechat_msg.like) as like_max," .
            "min(wechat_msg.read) as read_min," .
            "min(wechat_msg.like) as like_min";
        $where = [['del_flag', '<>', 4]];
        $accountId && $where[] = ['account_id',$accountId];
        $startDate && $where[] = ['pub_time', '>=', $startDate];
        $endDate && $where[] = ['pub_time', '<', $endDate];
        $msgList = WechatMsgTable::selectRaw($selectColmun)
            ->join('wechat_account', 'wechat_msg.account_id', '=', 'wechat_account.id') 
            ->where($where)
            ->whereNotNull('read')
            ->whereNotNull('like')
            ->groupBy('wechat_msg.account_id')
            ->orderBy('wechat_msg.account_id')
            ->get();
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }
}
