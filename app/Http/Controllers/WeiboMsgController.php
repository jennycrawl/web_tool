<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WeiboAccount as WeiboAccountTable;
use App\Models\WeiboFeed as WeiboFeedTable;

class WeiboMsgController extends Controller
{
    //
    public function getList(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $sortField = $request->query('sort_field', 'weibo_feed.id');
        $sortOrder = in_array(strtolower($request->query('sort_order')),['asc','desc']) ? $request->query('sort_order') : 'asc';
        $perPage = $request->query('per_page', 20);

        $accountId = intval($request->query('account_id','0'));
        $startDate = $request->query('start_date','');
        $endDate = $request->query('end_date','');
        $startDate && $startDate = date("Y-m-d H:i:s",strtotime($startDate));
        $endDate && $endDate = date("Y-m-d H:i:s",strtotime($endDate) + 86400);
        /*$selectColmun = "weibo_account.name," .
            "weibo_feed.id," .
            "weibo_feed.mid," .
            "weibo_feed.account_id," .
            "weibo_feed.forward," .
            "weibo_feed.comment," .
            "weibo_feed.like," .*/
        $where = [];
        $accountId && $where[] = ['account_id',$accountId];
        $startDate && $where[] = ['pubtime', '>=', $startDate];
        $endDate && $where[] = ['pubtime', '<', $endDate];
        $res = WeiboFeedTable::select(
            "weibo_account.name as account_name",
            "weibo_feed.id",
            "weibo_feed.mid",
            "weibo_feed.account_id",
            "weibo_feed.forward",
            "weibo_feed.comment",
            "weibo_feed.like",
            "weibo_feed.pubtime",
            "weibo_feed.update_time as crawl_time"
            )->join('weibo_account', 'weibo_feed.account_id', '=', 'weibo_account.id') 
            ->where($where)
            ->orderBy($sortField, $sortOrder)
            ->paginate($perPage)->toArray();
        $result = [
            'success' => true,
            'msg' => [
                'total'         => $res['total'],
                'total_page'    => $res['last_page'], 
                'current_page'  => $res['current_page'], 
                'msg_list'      => array_map(function($row) {
                    $row['url'] = sprintf('https://m.weibo.cn/detail/%s', $row['mid']);
                    $row['pubtime'] = date('Y-m-d', strtotime($row['pubtime']));
                    return $row;
                }, $res['data']),
            ]
        ];
        return $result;
    }
}
