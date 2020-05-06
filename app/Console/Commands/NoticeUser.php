<?php

namespace App\Console\Commands;

use App\Library\Logs;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class NoticeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:user_wallet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '命令描述：通知用户钱包余额不足';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $url = config('app.url') . '/wechat/notice/command_user_wallet';
        $client->get($url)->getBody()->getContents();
    }
}
