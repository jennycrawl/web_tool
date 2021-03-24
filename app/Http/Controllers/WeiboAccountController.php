<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WeiboAccount as WeiboAccountTable;
use App\Models\WeiboFeed as WeiboFeedTable;

class WeiboAccountController extends Controller
{
    //
    public function getAccountList(Request $request)
    {
        //$msgList = WeiboAccountTable::get()->toArray();
        $msgList = array_map(function($row) {
            $row['url'] = sprintf('https://m.weibo.cn/u/%s', $row['uid']);
            $row['crawl_time'] = date('Y-m-d', strtotime($row['update_time']));
            return $row;
        }, WeiboAccountTable::where('status', '=', WeiboAccountTable::STATUS_VALID)->get()->toArray());
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }

    public function insertAccount(Request $request)
    {
        $name = $request->input('name', '');
        $uid = $request->input('uid', '');
        if (WeiboAccountTable::where([
            ['uid', $uid],
            ])->exists()) {
            return ['success' => false, 'msg' => 'uid已存在'];
        }
        if (empty($name) || empty($uid)) {
            return ['success' => false, 'msg' => '参数错误1'];
        }
        WeiboAccountTable::insert(
            [
                'name'  => $name,
                'uid'   => $uid,
            ]);
        $result = ['success' => true, 'msg' => '修改成功'];
        return $result;
    }

    public function updateAccount($id, Request $request)
    {
        $id = intval($id);
        $account = WeiboAccountTable::where('id',$id)->first();
        if (!$account) {
            //return response(['success' => false, 'msg' => '账号不存在'], 400)
            //    ->header('Content-Type', 'application/json');
            return ['success' => false, 'msg' => '账号不存在'];
        }
        $name = $request->input('name', '');
        $uid = $request->input('uid', '');
        if (WeiboAccountTable::where([
            ['uid', $uid],
            ['id', '<>', $id]
            ])->exists()) {
            return ['success' => false, 'msg' => 'uid已存在'];
        }
        if (empty($name) || empty($uid)) {
            return ['success' => false, 'msg' => '参数错误'];
        }
        if ($name != $account['name'] || $uid != $account['uid']) {
            WeiboAccountTable::where('id', $id)
                ->update([
                    'name'  => $name,
                    'uid'   => $uid,
                ]);
        }
        $result = ['success' => true, 'msg' => '修改成功'];
        return $result;
    }
    public function deleteAccount($id, Request $request)
    {
        $id = intval($id);
        $account = WeiboAccountTable::where('id',$id)->first();
        if (!$account) {
            return ['success' => false, 'msg' => '账号不存在'];
        }
        DB::beginTransaction();
        try {
            $res1 = WeiboAccountTable::where('id', $id)->delete();
            if (WeiboFeedTable::where('account_id', $id)->exists()) {
                $res2 = WeiboFeedTable::where('account_id', $id)->delete();
            } else {
                $res2 = 1;
            }
            if ($res1 && $res2) {
                DB::commit();
            } else {
                throw new \Exception('删除失败');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
        return ['success' => true, 'msg' => '删除成功'];
    }
}
