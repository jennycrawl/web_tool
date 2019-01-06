<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WechatAccount as WechatAccountTable;

class WechatAccountController extends Controller
{
    //
    public function getAccountList(Request $request)
    {
        $msgList = WechatAccountTable::get();    
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }
}
