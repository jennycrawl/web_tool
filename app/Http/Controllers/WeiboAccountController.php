<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeiboAccount as WeiboAccountTable;

class WeiboAccountController extends Controller
{
    //
    public function getAccountList(Request $request)
    {
        $msgList = WeiboAccountTable::get();    
        $result = ['success' => true, 'msg' => $msgList];
        return $result;
    }
}
