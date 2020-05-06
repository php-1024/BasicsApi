<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Logs;
use App\Models\OperationLog;
use App\Models\Question;
use App\Models\QuestionThumb;
use App\Models\Suggest;
use App\Models\UserCooperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * 合作消息列表
     * @param Request $request
     * @return array
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/10/22 10:21
     */
    public function cooperation_list(Request $request)
    {
        $list = UserCooperation::getPaginate([], [], 10, 'id', 'DESC');
        $num  = UserCooperation::getCount(['status' => 0]);
        return ['code' => 20000, 'message' => 'ok', 'data' => ['list' => $list, 'num' => $num]];
    }


    /**
     * 合作消息查阅状态处理
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/10/22 10:44
     */
    public function cooperation_status(Request $request)
    {
        $id   = $request->get('id');
        $info = $request->get('info');
        DB::beginTransaction();
        try {
            UserCooperation::EditData(['id' => $id], ['status' => 1]);
            OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => '处理了ID为【' . $id . '】的合作留言消息']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('查阅失败', $e);
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '操作成功！'];
    }

    /**
     * 吸粉客户反馈的问题列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
    public function hifans_question_list(Request $request)
    {
        $list = Question::where(['question.type' => 1])->leftJoin('user', function ($join) {
            $join->on('question.user_id', '=', 'user.id');
        })
            ->select(['question.*', 'user.mobile'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        return ['code' => 20000, 'data' => $list];
    }

    /**
     * 获取问题详情
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:53
     */
    public function question_detail(Request $request)
    {
        $id              = $request->get('id');
        $detail          = Question::getOne(['id' => $id]);
        $thumb           = QuestionThumb::getList(['question_id' => $id], 'thumb');
        $detail['thumb'] = $thumb;
        return ['code' => 20000, 'detail' => $detail];
    }

    /**
     * 处理客户工单
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:53
     */
    public function hifans_question_status(Request $request)
    {
        $id    = $request->get('id');
        $reply = $request->get('reply');
        $info  = $request->get('info');
        if (empty($reply)) {
            return ['code' => 50000, 'message' => '请输入您要回复的内容，内容不能为空哦！'];
        }
        DB::beginTransaction();
        try {
            Question::EditData(['id' => $id], [
                'reply'  => $reply,
                'status' => 1
            ]);
            OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => '处理了ID为【' . $id . '】的客户工单']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('处理客户工单失败', $e);
        }
        return ['code' => 20000, 'message' => '处理工单成功', 'data' => 'ok'];
    }

    /**
     * 留言列表
     * @param Request $request
     * @return array
     */
    public function suggest_list(Request $request)
    {
        $list = Suggest::getPaginate([], [], 10, 'created_at', 'DESC');
        $num  = Suggest::getCount(['status' => 0]);
        return ['code' => 20000, 'message' => 'ok', 'data' => ['list' => $list, 'num' => $num]];
    }


    /**
     * 状态修改
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function suggest_status(Request $request)
    {
        $id   = $request->get('id');
        $info = $request->get('info');
        DB::beginTransaction();
        try {
            Suggest::EditData(['id' => $id], ['status' => 1]);
            OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => '处理了ID为【' . $id . '】的留言建议']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('查阅失败', $e);
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '操作成功！'];
    }
}