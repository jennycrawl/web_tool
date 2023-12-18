<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WechatAccount as WechatAccountTable;
use App\Models\WechatMsg as WechatMsgTable;

class WechatMsgController extends Controller
{
    //
    public function getList(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $sortField = $request->query('sort_field', 'wechat_msg.id');
        $sortOrder = in_array(strtolower($request->query('sort_order')),['asc','desc']) ? $request->query('sort_order') : 'asc';
        $perPage = $request->query('per_page', 20);

        $accountId = intval($request->query('account_id','0'));
        $startDate = $request->query('start_date','');
        $endDate = $request->query('end_date','');
        $startDate && $startDate = date("Y-m-d H:i:s",strtotime($startDate));
        $endDate && $endDate = date("Y-m-d H:i:s",strtotime($endDate) + 86400);
        $where = [['del_flag', '<>', 4]];
        $accountId && $where[] = ['account_id',$accountId];
        $startDate && $where[] = ['pub_time', '>=', $startDate];
        $endDate && $where[] = ['pub_time', '<', $endDate];
        $res = WechatMsgTable::select(
            "wechat_account.name as account_name",
            "wechat_msg.id",
            "wechat_msg.title",
            "wechat_msg.content_url",
            "wechat_msg.read",
            "wechat_msg.like",
            "wechat_msg.pub_time",
            "wechat_msg.update_time as crawl_time"
            )->join('wechat_account', 'wechat_msg.account_id', '=', 'wechat_account.id') 
            ->where($where)
            ->whereNotNull('read')
            ->whereNotNull('like')
            ->orderBy($sortField, $sortOrder)
            ->paginate($perPage)->toArray();
        $result = [
            'success' => true,
            'msg' => [
                'total'         => $res['total'],
                'total_page'    => $res['last_page'], 
                'current_page'  => $res['current_page'], 
                'msg_list'      => $res['data'],
            ]
        ];
        return $result;
    }
}
