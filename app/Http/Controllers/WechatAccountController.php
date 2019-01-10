<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WechatAccount as WechatAccountTable;
use App\Models\WechatMsg as WechatMsgTable;

class WechatAccountController extends Controller
{
    //
    public function getAccountList(Request $request)
    {
        $msgList = WechatAccountTable::get();    
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }

    public function insertAccount(Request $request)
    {
        $name = $request->input('name', '');
        $biz = $request->input('biz', '');
        if (WechatAccountTable::where([
            ['biz', $biz],
            ])->exists()) {
            return ['success' => false, 'msg' => 'biz已存在'];
        }
        if (empty($name) || empty($biz)) {
            return ['success' => false, 'msg' => '参数错误'];
        }
        WechatAccountTable::insert(
            [
                'name'  => $name,
                'biz'   => $biz,
            ]);
        $result = ['success' => true, 'msg' => '修改成功'];
        return $result;
    }

    public function updateAccount($id, Request $request)
    {
        $id = intval($id);
        $account = WechatAccountTable::where('id',$id)->first();    
        if (!$account) {
            //return response(['success' => false, 'msg' => '账号不存在'], 400)
            //    ->header('Content-Type', 'application/json');
            return ['success' => false, 'msg' => '账号不存在'];
        }
        $name = $request->input('name', '');
        $biz = $request->input('biz', '');
        if (WechatAccountTable::where([
            ['biz', $biz],
            ['id', '<>', $id]
            ])->exists()) {
            return ['success' => false, 'msg' => 'biz已存在'];
        }
        if (empty($name) || empty($biz)) {
            return ['success' => false, 'msg' => '参数错误'];
        }
        if ($name != $account['name'] || $biz != $account['biz']) {
            WechatAccountTable::where('id', $id)
                ->update([
                    'name'  => $name,
                    'biz'   => $biz,
                ]);
        }
        $result = ['success' => true, 'msg' => '修改成功'];
        return $result;
    }
    public function deleteAccount($id, Request $request)
    {
        $id = intval($id);
        $account = WechatAccountTable::where('id',$id)->first();    
        if (!$account) {
            return ['success' => false, 'msg' => '账号不存在'];
        }
        DB::beginTransaction();
        try {
            $res1 = WechatAccountTable::where('id', $id)->delete();
            if (WechatMsgTable::where('account_id', $id)->exists()) {
                $res2 = WechatMsgTable::where('account_id', $id)->delete();
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
