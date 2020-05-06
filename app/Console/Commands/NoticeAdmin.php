<?php

namespace App\Console\Commands;

use App\Library\Logs;
use App\Models\FansOrder;
use App\Models\OfficialAccount;
use App\Models\OfficialUnusual;
use App\Models\UserBind;
use EasyWeChat\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NoticeAdmin extends Command
{
    protected $officialAccount;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:unusual_official';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '命令描述：通知管理员异常的公众号，需要进行人工检测';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $config                = [
            'app_id'  => config('product.OfficialAccount.app_id'),
            'secret'  => config('product.OfficialAccount.secret'),
            'token'   => config('product.OfficialAccount.token'),
            'aes_key' => config('product.OfficialAccount.aes_key')
        ];
        $this->officialAccount = Factory::officialAccount($config);
    }

    /**
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/12/5 16:11
     */
    public function handle()
    {
        $app       = $this->officialAccount;
        $official  = OfficialAccount::getList(['status' => 1]);
        $user_bind = UserBind::getPluck(['user_id' => 1, 'status' => 1], 'openid');
        try {
            foreach ($official as $key => $val) {
                // 计算处理公众号未消费的订单数量
                $count = FansOrder::getCount(['appid' => $val['appid'], 'status' => 0]);
                if (OfficialUnusual::checkRowExists(['official_id' => $val['id']])) {
                    OfficialUnusual::EditData(['official_id' => $val['id']], ['user_id' => $val['user_id'], 'num' => $count]);
                } else {
                    OfficialUnusual::AddData([
                        'user_id'     => $val['user_id'],
                        'official_id' => $val['id'],
                        'appid'       => $val['appid'],
                        'name'        => $val['name'],
                        'qrcode_path' => $val['qrcode_path'],
                        'num'         => $count,
                    ]);
                }
                if ($count > 50) {
                    // 检测出当前计划任务的公众号中，如果有大于十条订单未消费，就通知管理员去查看该公众号是否异常
                    foreach ($user_bind as $kk => $vv) {
                        $re = $app->template_message->send([
                            'touser'      => $vv,
                            'template_id' => config('product.OfficialAccount.admin_template_id'),
                            'data'        => [
                                'first'       => ["粉丝万岁提醒您，当前公众号【{$val['appid']}】-【{$val['name']}】，可能存在异常，请您及时确认！", '#CC0000'],
                                'performance' => ["当前公众号有{$count}条未消费的订单", '#CC0000'],
                                'time'        => [date('Y-m-d H:i:s'), '#CC0000'],
                                'remark'      => ['请您及时处理该公众号，以免影响系统的正常运行！', '#CC0000'],
                            ],
                        ]);
                        if ($re['errcode'] != 0) {
                            Logs::error('模板消息通知发送失败', json_encode($re));
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
            \Log::error('管理员专用模板通知失败！');
        }
    }
}
