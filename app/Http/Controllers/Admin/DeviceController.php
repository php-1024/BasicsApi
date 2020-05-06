<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceTemplate;
use App\Models\OperationLog;
use App\Models\PlanTask;
use App\Models\Province;
use App\Models\Scene;
use App\Models\UserOperationLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DeviceController extends Controller
{
    /**
     * 获取设备列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:44
     */
    public function get_device(Request $request)
    {
        $page  = $request->get('page');
        $limit = $request->get('limit');
        $list  = Device::where([])
            ->leftJoin('account_info', function ($join) {
                $join->on('device.account_id', '=', 'account_info.account_id');
            })
            ->leftJoin('device_category', function ($join) {
                $join->on('device.category_id', '=', 'device_category.id');
            })
            ->select(['account_info.company', 'device_category.name as category_name', 'device.*'])
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => $list];
    }

    /**
     * 获取设备分类
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:45
     */
    public function get_category(Request $request)
    {
        $id   = $request->get('id');
        $info = $request->get('info');
        $data = Device::getOne(['id' => $id, 'account_id' => $info['id']], ['name', 'category_id']);
        if (!$data) {
            $data = [];
        }
        $list = DeviceCategory::getList([], ['id', 'name']);
        return ['code' => 20000, 'data' => ['category' => $list, 'edit_data' => $data]];
    }

    /**
     * 获取场景
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:45
     */
    public function get_scene(Request $request)
    {
        $id    = $request->get('id');
        $info  = $request->get('info');
        $scene = Device::getValue(['id' => $id, 'account_id' => $info['id']], 'scene');
        if (!$scene) {
            $scene = [];
        }
        $list = Scene::getList([], ['id', 'name']);
        return ['code' => 20000, 'data' => ['list' => $list, 'scene' => $scene]];
    }

    /**
     * 模板详情
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:45
     */
    public function template_detail(Request $request)
    {
        $id       = $request->get('id');
        $template = DeviceTemplate::getOne(['device_id' => $id]);
        return ['code' => 20000, 'template' => $template];
    }


    /**
     * 修改设备单价
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @time：2020/3/11 11:22
     */
    public function edit_price(Request $request)
    {
        $info  = $request->get('info');
        $id    = $request->get('id');
        $price = $request->get('price');
        if (empty($price)) {
            return ['code' => 50000, 'message' => '请您填写设备底价，该项不能为空！'];
        }
        if ($price < 0) {
            return ['code' => 50000, 'message' => '底价填写有误，请您确认后再试！'];
        }
        $new_price = $price * 100;
        // 开启事务回滚
        DB::beginTransaction();
        try {
            $old_price = Device::getValue(['id' => $id], 'price');
            if ($new_price != $old_price) {
                $old_price = $old_price / 100;
                Device::EditData(['id' => $id], ['price' => $new_price]);
                OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了ID为：{$id}的设备单价，单价从{$old_price}元变更为{$price}元"]);
            }
            // 提交数据
            DB::commit();
        } catch (\Exception $e) {
            // 提交出错 回滚数据
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败请稍后再试！' . $e->getMessage()];
        }
        return ['code' => 20000, 'message' => '操作成功！'];
    }
}
