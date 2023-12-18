<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeiboAccount extends Model
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;
    //
    protected $table = 'weibo_account';
    public $timestamps = false;
}
