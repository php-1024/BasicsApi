<?php

namespace App\Http\Controllers\Merchant;

use App\Models\City;
use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceTemplate;
use App\Models\OperationLog;
use App\Models\Province;
use App\Models\Scene;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DeviceController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * 获取设备列表
     */
    public function get_device(Request $request)
    {
        $info  = $request->get('info');
        $page  = $request->get('page');
        $limit = $request->get('limit');
        $list  = Device::getPaginate(['account_id' => $info['id']], [], ['limit' => $limit, 'page' => $page], 'created_at')->toArray();
        foreach ($list['data'] as &$val) {
            $val['category_name'] = DeviceCategory::getValue(['id' => $val['category_id']], 'name');
        }
        return ['code' => 20000, 'data' => $list];
    }

    /**
     * @param Request $request
     * @return array
     * 获取设备分类
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
     * @param Request $request
     * @return array
     * 获取省市区信息
     */
    public function get_address(Request $request)
    {
        $id             = $request->get('id');
        $info           = $request->get('info');
        $defaultChecked = Device::getValue(['id' => $id, 'account_id' => $info['id']], 'address');
        if (!$defaultChecked) {
            $defaultChecked = [];
        }
        $province = Province::where([])->get(['id', 'name'])->toArray();
        $city     = City::where([])->get(['id', 'province_id', 'name'])->toArray();
        $address  = [];
        foreach ($province as $key => $val) {
            foreach ($city as $k => $v) {
                if ($val['id'] == $v['province_id']) {
                    $val['children'][] = $v;
                    unset($city[$k]);
                }
            }
            unset($province[$key]);
            $address[] = $val;
        }
        return ['code' => 20000, 'data' => ['address' => $address, 'defaultChecked' => $defaultChecked]];
    }

    /**
     * @param Request $request
     * @return array
     * 获取场景
     */
    public function get_scene(Request $request)
    {
        $id    = $request->get('id');
        $info  = $request->get('info');
        $scene = Device::getValue(['id' => $id, 'account_id' => $info['id']], 'scene');
        $price = Device::getValue(['id' => $id, 'account_id' => $info['id']], 'price');
        if (!$scene) {
            $scene = [];
        }
        $list = Scene::getList([], ['id', 'name']);
        return ['code' => 20000, 'data' => ['list' => $list, 'scene' => $scene, 'price' => $price]];
    }


    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * 添加设备
     */
    public function add_device(Request $request)
    {
        $name        = $request->get('name');
        $category_id = $request->get('category_id');
        $address     = $request->get('address');
        $address     = implode(",", $address);
        $scene       = $request->get('scene');
        $scene       = implode(",", $scene);
        $price       = $request->get('price');
        $key         = Uuid::uuid1()->getHex();
        $info        = $request->get('info');
        $data        = [
            'account_id'  => $info['id'],
            'name'        => $name,
            'category_id' => $category_id,
            'address'     => $address,
            'scene'       => $scene,
            'price'       => $price * 100,
            'key'         => $key,
        ];
        DB::beginTransaction();
        try {
            Device::AddData($data);
            OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '添加了一台设备，设备名称为' . $name]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 5000, 'message' => '添加失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '添加设备成功！'];
    }


    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * 编辑设备
     */
    public function edit_device(Request $request)
    {
        $id          = $request->get('id');
        $name        = $request->get('name');
        $category_id = $request->get('category_id');

        $address = $request->get('address');
        $address = implode(",", $address);
        $scene   = $request->get('scene');
        $scene   = implode(",", $scene);
        $price   = $request->get('price');
        $info    = $request->get('info');
        $data    = [
            'name'        => $name,
            'category_id' => $category_id,
            'address'     => $address,
            'scene'       => $scene,
            'price'       => $price * 100,
        ];
        DB::beginTransaction();
        try {
            Device::EditData(['id' => $id, 'account_id' => $info['id']], $data);
            OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '修改了一台设备，设备的ID为' . $id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 5000, 'message' => '修改失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '修改设备成功！'];
    }

    /**
     * @param Request $request
     * @return array
     * 模板详情
     */
    public function template_detail(Request $request)
    {
        $id       = $request->get('id');
        $template = DeviceTemplate::getOne(['device_id' => $id]);
        return ['code' => 20000, 'template' => $template];
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * 编辑模板
     */
    public function edit_template(Request $request)
    {
        $info            = $request->get('info');
        $device_id       = $request->get('device_id');
        $title           = $request->get('title');
        $type            = $request->get('type');
        $desc            = $request->get('desc');
        $thumb           = $request->get('thumb');
        $url             = $request->get('url');
        $content         = $request->get('content');
        $device_template = DeviceTemplate::checkRowExists(['device_id' => $device_id]);
        DB::beginTransaction();
        try {
            if ($device_template) {
                DeviceTemplate::EditData(['device_id' => $device_id], [
                    'title'   => $title,
                    'type'    => $type,
                    'desc'    => $desc,
                    'thumb'   => $thumb,
                    'url'     => $url,
                    'content' => $content,
                ]);
                OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '编辑了设备ID为【' . $device_id . '】的消息模板']);
            } else {
                DeviceTemplate::AddData([
                    'device_id' => $device_id,
                    'title'     => $title,
                    'type'      => $type,
                    'desc'      => $desc,
                    'thumb'     => $thumb,
                    'url'       => $url,
                    'content'   => $content,
                ]);
                OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '首次设置了设备ID为【' . $device_id . '】的消息模板']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '操作成功'];

    }
}
