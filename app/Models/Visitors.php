<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    public $timestamps = false;
    protected $fillable = ['ip_address', 'url', 'device', 'user_agent', 'visited_at'];

    // Query helper
    public static function todayCount()
    {
        return self::whereDate('visited_at', today())->count();
    }

    public static function totalCount()
    {
        return self::count();
    }
}
