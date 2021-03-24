<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WeiboAccount as WeiboAccountTable;
use App\Models\WeiboFeed as WeiboFeedTable;

class WeiboStatisticsController extends Controller
{
    //
    public function getStatistics(Request $request)
    {
        $accountId = intval($request->query('account_id','0'));
        $startDate = $request->query('start_date','');
        $endDate = $request->query('end_date','');
        $startDate && $startDate = date("Y-m-d H:i:s",strtotime($startDate));
        $endDate && $endDate = date("Y-m-d H:i:s",strtotime($endDate) + 86400);
        $selectColmun = "weibo_account.name," .
            "weibo_account.id as id," .
            "weibo_account.attention as attention," .
            "weibo_account.fans as fans," .
            "weibo_account.feed as feed," .
            "weibo_account.update_time as crawl_time," .
            "count(weibo_feed.id) as count," .
            "sum(weibo_feed.forward) as forward_sum," .
            "sum(weibo_feed.comment) as comment_sum," .
            "sum(weibo_feed.like) as like_sum," .
            "avg(weibo_feed.forward) as forward_avg," .
            "avg(weibo_feed.comment) as comment_avg," .
            "avg(weibo_feed.like) as like_avg," .
            "max(weibo_feed.forward) as forward_max," .
            "max(weibo_feed.comment) as comment_max," .
            "max(weibo_feed.like) as like_max," .
            "min(weibo_feed.forward) as forward_min," .
            "min(weibo_feed.comment) as comment_min," .
            "min(weibo_feed.like) as like_min";
        $where = [];
        $accountId && $where[] = ['account_id',$accountId];
        $startDate && $where[] = ['pubtime', '>=', $startDate];
        $endDate && $where[] = ['pubtime', '<', $endDate];
        $msgList = WeiboFeedTable::selectRaw($selectColmun)
            ->join('weibo_account', 'weibo_feed.account_id', '=', 'weibo_account.id') 
                ->where('weibo_account.status', '=', WeiboAccountTable::STATUS_VALID)
            ->where($where)
            ->groupBy('weibo_feed.account_id')
            ->orderBy('weibo_feed.account_id')
            ->get();
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }
}
