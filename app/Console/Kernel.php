<?php

namespace App\Console;

use App\Library\Logs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\NoticeUser::class,
        \App\Console\Commands\NoticeAdmin::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 消费记录日报表生成
        $schedule->command('chart:day_consume')
            ->timezone('Asia/Shanghai')
            ->twiceDaily(1, 13); // 每天的 1:00 和 13:00 分别执行一次任务

        // 合作商日报表生成
        $schedule->command('chart:day_account')
            ->timezone('Asia/Shanghai')
            ->twiceDaily(1, 13); // 每天的 1:00 和 13:00 分别执行一次任务

        // 公众号日报表生成
        $schedule->command('chart:day_official')
            ->timezone('Asia/Shanghai')
            ->twiceDaily(1, 13); // 每天的 1:00 和 13:00 分别执行一次任务

        // 合作商月报表执行生成脚本
        $schedule->command('chart:month_account')
            ->timezone('Asia/Shanghai')
            ->twiceDaily(1, 13); // 每天的 1:00 和 13:00 分别执行一次任务

        // 用户钱包余额不足通知
        $schedule->command('notice:user_wallet')
            ->timezone('Asia/Shanghai')
            ->cron('20 18 * * *')->then(function () {
                // 每天下午六点20开始检测用户钱包余额，余额不足10块钱的用户，将会收到微信公众号的模板消息通知
                Logs::notice('账户余额通知', '通知送达');
            });

        // 异常公众号检测通知
        $schedule->command('notice:unusual_official')
            ->timezone('Asia/Shanghai')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
