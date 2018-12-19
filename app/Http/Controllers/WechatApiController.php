<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatApiController extends Controller
{
    //
    public function getMsgJson()
    {
        return json_encode(['name'=>'hu']);
    }
}
