<?php
namespace xiang\rbac;
use DB;
class Role{
    /**
     * desc : 角色展示
     * auth : weiyang
     * time : 2017/2/9 14:45
     * @return array
     */
    public function roleShow()
    {
        $data = DB::table('qx_role')->get();
        $role = $this->roleShows($data, $id = 0, $level = 0);
        return $role;
    }

    /**
     * 递归
     * @param $data
     * @param int $id
     * @param int $level
     * @return array
     */
    private function roleShows($data, $id = 0, $level = 0)
    {

        static $arr = array();
        foreach ($data as $key => $v) {
            if ($v->pid == $id) {
                $v->level = $level;
                $arr[] = $v;
                $this->roleShows($data, $v->id, $level + 1);
            }
        }
        return $arr;
    }

    /**
     * desc : 角色条件搜索
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function roleSelect($data)
    {
        $role = DB::table('qx_role')
            ->where('name', 'like', '%' . $data['name'] . '%')
            ->get();
        if (empty($role)) {
            return 0;
        } else {
            return $role;
        }
    }

    /**
     * desc : 角色添加页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @return mixed
     */
    public function roleFrom()
    {
        $role = DB::table('qx_role')
            ->where('pid', 0)
            ->where('status', 1)
            ->get();
        return $role;
    }

    /**
     * desc : 角色添加
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function roleInsert($data)
    {
        $role = DB::table('qx_role')->insert(
            [
                'name' => $data['name'],
                'pid' => $data['pid'],
                'status' => $data['status']
            ]
        );
        if ($role == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 角色删除
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return int
     */
    public function roleDelete($id)
    {
        $role = DB::table('qx_role')->where('pid', $id)
            ->get()->toArray();
        if (!empty($role)) {
            for ($i = 0; $i < count($role); $i++) {
                DB::table('qx_role')->where('id', $role[$i]['id'])
                    ->delete();
            }
        }
        $delate = DB::table('qx_role')->where('id', $id)
            ->delete();
        if ($delate == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 角色修改页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return mixed
     */
    public function roleFroms($id)
    {
        $pid = DB::table('qx_role')->where('id', $id)->get()->toArray();
        if ($pid[0]['pid'] == 0) {
            $role = DB::table('qx_role')->where('id', $id)->get();
        } else {
            $role = DB::select(
                'SELECT r.status,r.id,r.name,r.pid,rs.name as pname
                FROM brd_qx_role AS r INNER JOIN brd_qx_role AS rs ON rs.id = r.pid
                WHERE r.id =' . $id
                );
        }
        return $role;
    }

    /**
     * desc : 角色修改
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function roleUpdate($data)
    {
        $role = DB::table('qx_role')->where('id', $data['id'])
            ->update([
                'name' => $data['name'],
                'status' => $data['status']
            ]);
        if ($role == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
