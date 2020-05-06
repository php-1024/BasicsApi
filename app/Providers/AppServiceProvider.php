<?php

namespace App\Providers;

use App\Library\Logs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Carbon::setLocale('zh');
        //打印慢于1000ms的sql查询（即1s的查询语句)
        DB::listen(function ($query) {
            $sql = $query->sql;
            $time = $query->time;
            if ($time > 1000) {
                $re_sql = var_export(compact('sql'), TRUE);
                Logs::error('当前页面sql查询耗时为', $time . "ms\r\n" . $re_sql);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
