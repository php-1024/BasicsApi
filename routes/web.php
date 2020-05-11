<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Iszmxw\MysqlMarkdown\Mysql;

Route::any('/', function () {
    return view('pass');
});

// 数据库文档
Route::get('mysql', function () {
    $config = [
        'dbs'      => env('DB_CONNECTION'),
        'port'     => env('DB_PORT'),
        'charset'  => 'utf8',
        'host'     => env('DB_HOST'),
        'name'     => env('DB_DATABASE'),
        'user'     => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD')
    ];
    Mysql::markdown($config);
});

Route::get('phpinfo', function () {
    phpinfo();
});

// 下载图片到本地
Route::get('test', function () {
    $qrcode_url = "http://mmbiz.qpic.cn/mmbiz_jpg/2Uw5rqyC73bhPJNPtxnoP9rVhJmbM4ib7VQXKt7rbLicfGHfsibHeHZiaIqDBiae3LTtfBl2BYaWOsGS4aibAVrR5g2Q/0";
    $ext        = strrchr($qrcode_url, '.');
    \App\Library\Upload::download($qrcode_url, "./uploads/wechat/", "1.jpg");
});