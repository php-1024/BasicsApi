<?php

namespace App\Http\Controllers\Admin;

use App\Models\OperationLog;
use App\Models\Role;
use App\Models\RoleRoute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * 角色列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:54
     */
    public function list(Request $request)
    {
        $list = Role::getPaginate([], [], 10, 'created_at');
        return ['code' => 20000, 'data' => $list];
    }


    /**
     * 角色对应路由
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:54
     */
    public function routes(Request $request)
    {
        $id              = $request->get('id');
        $routes          = Role::getValue(['id' => $id], 'routes');
        $user_route_list = explode(',', $routes);
        $all_route_list  = RoleRoute::where([])->get(['id', 'is_menu', 'name', 'parent_id'])->toArray();
        if ($id == 1) {
            $disabled = true;
        } else {
            $disabled = false;
        }
        $all_route_list = self::getTree($all_route_list, 0, $disabled);
        return ['code' => 20000, 'data' => ['defaultChecked' => $user_route_list, 'all_route_list' => $all_route_list]];
    }

    /**
     * 编辑角色
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:54
     */
    public function edit(Request $request)
    {
        $info   = $request->get('info');
        $id     = $request->get('id');
        $name   = $request->get('name');
        $routes = $request->get('routes');
        $desc   = $request->get('desc');
        $routes = implode(',', $routes);
        //开启事务回滚
        DB::beginTransaction();
        try {
            Role::EditData(['id' => $id], ['name' => $name, 'routes' => $routes, 'desc' => $desc]);
            OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => '编辑了角色ID为【' . $id . '】的角色信息']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 50000, 'message' => '修改失败！请稍后再试'];
        }
        return ['code' => 20000, 'message' => '修改成功'];
    }

    /**
     * 递归生成菜单结构
     * @param $data
     * @param $parent_id
     * @param $disabled
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:54
     */
    public static function getTree($data, $parent_id, $disabled)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            $v['disabled'] = $disabled;
            if ($v['parent_id'] == $parent_id) { //父亲找到儿子
                $v['children'] = self::getTree($data, $v['id'], $disabled);
                $tree[]        = $v;
                unset($data[$k]);
            }
        }
        return $tree;
    }
}
