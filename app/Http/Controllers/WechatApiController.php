<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WechatMsg;

class WechatApiController extends Controller
{
    //
    public function pushMsgJson(Request $request)
    {
        $result = [];
        $input = $request->post();
        if (empty($input['biz']) || empty($input['msg_list'])) {
            $result = ['success' => false,'msg' => '缺少参数'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        $biz = $input['biz'];
        $account = DB::table('wechat_account')->where('biz', $biz)->first();
        if (empty($account)) {
            $result = ['success' => false,'msg' => 'biz不在采集列表中'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        #$account = DB::select('select * from wechat_account where biz = ?', [$biz]);
        $currentMsgList = array_column(DB::select('select * from wechat_msg where account_id = ?', [$account->id]), NULL, 'fileid');
        $currentCrawlQueue = array_column(DB::select('select * from wechat_crawl_queue;', []), NULL,'content_url');
        $currentTime = date('Y-m-d H:i:s');

        $msgList = json_decode(htmlspecialchars_decode(urldecode($input['msg_list'])),true);//首先进行json_decode
        foreach ($msgList['list'] as $msg) {
            $pubTime        = date('Y-m-d H:i:s', $msg['comm_msg_info']['datetime']);
            $fileid         = $msg['app_msg_ext_info']['fileid'];
            $title          = $msg['app_msg_ext_info']['title'];
            $titleEncode    = urlencode(str_replace("&nbsp;", "", $msg['app_msg_ext_info']['title']));
            $contentUrl     = str_replace("\\", "", htmlspecialchars_decode($msg['app_msg_ext_info']['content_url']));
            $sourceUrl      = str_replace("\\", "", htmlspecialchars_decode($msg['app_msg_ext_info']['source_url']));
            $delFlag       = $msg['app_msg_ext_info']['del_flag'];
            if (empty($contentUrl)) {
                continue;
            }
            if (!isset($currentMsgList[$fileid])) {
                DB::table('wechat_msg')->insert(
                    [
                        'account_id'    => $account->id, 
                        'fileid'        => $fileid,
                        'title'         => $title,
                        'title_encode'  => $titleEncode,
                        'content_url'   => $contentUrl,
                        'source_url'    => $sourceUrl,
                        'del_flag'      => $delFlag,
                        'crawl_status'  => 0,
                        'pub_time'      => $pubTime,
                        'create_time'   => $currentTime,
                        'update_time'   => $currentTime,
                    ]
                );
            } else {
                DB::table('wechat_msg')
                    ->where('id', $currentMsgList[$fileid]->id)
                    ->update([
                        'content_url'   => $contentUrl,
                        'crawl_status'  => 0,
                        'del_flag'      => $delFlag,
                    ]);
            }
        }
        DB::table('wechat_account')
            ->where('id', $account->id)
            ->update(['crawl_time' => $currentTime]);
        return json_encode(['success' => true, 'msg' => 'success']);
    }

    public function pushMsgExt(Request $request)
    {
        $result = [];
        $input = $request->post();
        if (empty($input['content_url']) || empty($input['msg_ext'])) {
            $result = ['success' => false,'msg' => '缺少参数'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        $contentUrl = htmlspecialchars_decode(urldecode($input['content_url']));
        parse_str(parse_url($contentUrl, PHP_URL_QUERY),$query);//解析url地址
        $biz = !empty($query['__biz']) ? $query['__biz'] : '';
        $sn = !empty($query['sn']) ? $query['sn'] : '';
        if (empty($biz) || empty($sn)) {
            $result = ['success' => false,'msg' => 'content_url中缺少__biz或sn'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        $account = DB::table('wechat_account')->where('biz', $biz)->first();
        if (empty($account)) {
            $result = ['success' => false,'msg' => 'biz不在采集列表中'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        
        $msgExt = json_decode(htmlspecialchars_decode(urldecode($input['msg_ext'])),true);//首先进行json_decode
        $read = isset($msgExt['appmsgstat']) && isset($msgExt['appmsgstat']['read_num']) ? $msgExt['appmsgstat']['read_num'] : NULL;
        $like = isset($msgExt['appmsgstat']) && isset($msgExt['appmsgstat']['read_num']) ? $msgExt['appmsgstat']['like_num'] : NULL;
        if (is_null($read) || is_null($like)) {
            $result = ['success' => false,'msg' => '没有阅读数或点赞数'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        $dbMsgList = DB::select('select wechat_msg.* from wechat_msg join wechat_account on wechat_msg.account_id = wechat_account.id where wechat_account.biz = ? and content_url like ? limit 1', [$biz, '%' . $sn . '%']);
        $dbMsg = $dbMsgList ? $dbMsgList[0] : [];
        if (empty($dbMsg)) {
            $result = ['success' => false,'msg' => 'wechat_msg数据不存在'];
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        
        $currentTime = date('Y-m-d H:i:s');
        if ($read != $dbMsg->read || $like != $dbMsg->like) {
            DB::table('wechat_msg')
                ->where('id', $dbMsg->id)
                ->update([
                    'read'          => intval($read),
                    'like'          => intval($like),
                    'crawl_status'  => 2,//抓取完成
                    'update_time'   => $currentTime,
                ]);
        }

        return json_encode(['success' => true, 'msg' => 'success']);
    }

    //getWxHis.php 当前页面为公众号历史消息时，读取这个程序
    //在采集队列表中有一个crawl_status字段，当值等于0时代表待读取
    //首先删除采集队列表中load=1的行
    //然后从队列表中任意select一行
    public function getWxHis(Request $request)
    {
        $queue = DB::table('wechat_msg')->where([
            ['crawl_status', 0],
            ['del_flag', '<', 4],
        ])->orderBy('account_id', 'asc')->first();
        if (empty($queue)) {
            $queue = DB::table('wechat_msg')->where([
                ['crawl_status', 1],
                ['del_flag', '<', 4],
            ])->orderBy('account_id', 'asc')->first();
        }
        if (empty($queue)) {
            $account = DB::table('wechat_account')->orderBy('crawl_time', 'asc')->first();
            $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=".$account->biz."&scene=3#wechat_redirect";//拼接公众号历史消息url地址（第二种页面形式）
        } else {
            $url = $queue->content_url;      
            DB::table('wechat_msg')
                ->where('id', $queue->id)
                ->update(['crawl_status' => 1]);
        }

        $result = [
            'success' => true,
            'msg' => $url,
        ];
        return json_encode($result);
    }

    //getWxPost.php 当前页面为公众号文章页面时，读取这个程序
    //首先删除采集队列表中load=1的行
    //然后从队列表中按照“order by id asc”选择多行(注意这一行和上面的程序不一样)
    public function getWxPost(Request $request)
    {
        $queue = DB::table('wechat_crawl_queue')->where('crawl_status', 0)->first();
        
        if (empty($queue)) {
            //$account = DB::table('wechat_account')->where('biz', $biz)->first();
            $url = '';//"https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=".$biz."&scene=124#wechat_redirect";//拼接公众号历史消息url地址（第二种页面形式）
        } else {
            $url = $queue->content_url;      
            DB::table('wechat_crawl_queue')
                ->where('id', $queue->id)
                ->update(['crawl_status' => 1]);
        }

        $result = [
            'success' => true,
            'msg' => sprintf('<script>setTimeout(function(){window.location.href="%s";},2000);</script>', $url)
        ];
        return $result['msg'];
        #return json_encode($result);
    }

    public function getMsgList(Request $request)
    {
        $msgList = WechatMsg::where('del_flag', '<', '4')->get();    
        var_dump($msgList);
    }
}
