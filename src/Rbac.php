<?php
namespace Rbac;
use DB;
class Rbac
{
    /**
     * desc : 角色分配展示
     * auth : weiyang
     * time : 2017/2/9 14:45
     * @return mixed
     */
    public function userroleShow()
    {
        $userrole= DB::select(
            "select u.name as uname,r.name as rname,u.id as uid,r.id as rid
             from brd_qx_user_role as ur
             inner join brd_qx_user as u on ur.user_id=u.id
             inner join brd_qx_role as r on ur.role_id=r.id
             ");
        return $userrole;
    }

    /**
     * desc : 角色分配添加页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @return array
     */
    public function userroleFrom()
    {
        $user = DB::select(
            'SELECT * FROM brd_qx_user where status=1 and id!=1'
        );
        $data = DB::select(
            'SELECT * FROM brd_qx_role where status=1'
        );
        $role = $this->accessShows($data, $id = 0, $level = 0);
        return array($user,$role);
    }

    /**
     * desc : 角色分配添加
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param Request $request
     */
    public function userroleInsert($data)
    {
        $num=count($data['rid']);
        for($i=0;$i<$num;$i++){
            $ur = DB::table('qx_user_role')->where([
                'user_id' => $data['uid'],
                'role_id' => $data['rid'][$i]
            ])->get()->toArray();
            if (empty($ur)) {
                 DB::table('qx_user_role')->insert([
                    'user_id' => $data['uid'],
                    'role_id' => $data['rid'][$i]
                ]);
            }
        }
    }

    /**
     * desc : 角色分配删除
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $uid
     * @param $rid
     * @return int
     */
    public function userroleDelete($uid,$rid)
    {
        $userrole = DB::table('qx_user_role')->where(['user_id'=>$uid,'role_id'=>$rid])
            ->delete();
        if ($userrole == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 角色分配修改页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return mixed
     */
    public function userroleFroms($id)
    {
        $pid = DB::table('qx_user_role')->where('id', $id)->get()->toArray();
        if ($pid[0]['pid'] == 0) {
            $userrole = DB::table('qx_user_role')->where('id', $id)->get();
        } else {
            $userrole = DB::select('SELECT r.sort,r.title,r.status,r.id,r.name,r.pid,rs.title as ptitle FROM brd_qx_user_role AS r INNER JOIN brd_qx_user_role AS rs ON rs.id = r.pid WHERE r.id =' . $id);
        }
        return $userrole;
    }

    /**
     * desc : 角色分配修改
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function userroleUpdate($data)
    {
        $userrole = DB::table('qx_user_role')->where('id', $data['id'])
            ->update([
                'name' => $data['name'],
                'status' => $data['status']
            ]);
        if ($userrole == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 权限分配展示
     * auth : weiyang
     * time : 2017/2/9 14:45
     * @return mixed
     */
    public function accessShow()
    {
        $access= DB::select(
            "select u.name as uname,u.id as uid,n.title as nname,r.name as rname,n.id as nid,r.id as rid
             from brd_qx_access as a
             inner join brd_qx_node as n on a.node_id=n.id
             left join brd_qx_role as r on a.role_id=r.id
             left join brd_qx_user as u on a.user_id=u.id
             ");
        return $access;
    }

    /**
     * desc : 权限分配添加页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @return array
     */
    public function accessFrom()
    {
        $node = DB::select(
            'SELECT * FROM brd_qx_node where status=1'
        );
        $user = DB::select(
            'SELECT * FROM brd_qx_user where status=1'
        );
        $nodes = $this->accessShows($node, $id = 0, $level = 0);
        $role = DB::select(
            'SELECT * FROM brd_qx_role where status=1'
        );
        $roles = $this->accessShowss($role, $id = 0, $level = 0);
        return array($nodes,$user,$roles);
    }

    /**
     * 递归
     * @param $data
     * @param int $id
     * @param int $level
     * @return array
     */
    private function accessShows($data, $id = 0, $level = 0)
    {

        static $arr = array();
        foreach ($data as $key => $v) {
            if ($v['pid'] == $id) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->accessShows($data, $v['id'], $level + 1);
            }
        }
        return $arr;
    }
    /**
     * 递归2
     * @param $data
     * @param int $id
     * @param int $level
     * @return array
     */
    private function accessShowss($data, $id = 0, $level = 0)
    {

        static $arr = array();
        foreach ($data as $key => $v) {
            if ($v['pid'] == $id) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->accessShowss($data, $v['id'], $level + 1);
            }
        }
        return $arr;
    }

    /**
     * desc : 权限分配添加
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     */
    public function accessInsert($data)
    {
        $num=count($data['nid']);
        if($data['uid']==-1){
            for($i=0;$i<$num;$i++) {
                $ur = DB::table('qx_access')->where([
                    'user_id' => $data['uid'],
                    'node_id' => $data['nid'][$i]
                ])->get()->toArray();
                if (empty($ur)) {
                    DB::table('qx_access')->insert([
                        'role_id' => $data['rid'],
                        'user_id' => 0,
                        'node_id' => $data['nid'][$i]
                    ]);
                }
            }
        }else{
            for($i=0;$i<$num;$i++) {
                $ur = DB::table('qx_access')->where([
                    'user_id' => $data['uid'],
                    'node_id' => $data['nid'][$i]
                ])->get()->toArray();
                if (empty($ur)) {
                    DB::table('qx_access')->insert([
                        'user_id' => $data['uid'],
                        'role_id' => 0,
                        'node_id' => $data['nid'][$i]
                    ]);
                }
            }
        }
    }

    /**
     * desc : 权限分配删除
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $nid
     * @param $id
     * @param $name
     * @return int
     */
    public function accessDelete($nid,$id,$name)
    {
        if($name=='u'){
            $access = DB::table('qx_access')->where(['node_id'=>$nid,'role_id'=>$id,'user_id'=>0])
                ->delete();
        }else{
            $access = DB::table('qx_access')->where(['node_id'=>$nid,'user_id'=>$id,'role_id'=>0])
                ->delete();
        }
        if ($access == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 权限分配修改页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return mixed
     */
    public function accessFroms($id)
    {
        $pid = DB::table('qx_access')->where('id', $id)->get()->toArray();
        if ($pid[0]['pid'] == 0) {
            $access = DB::table('qx_access')->where('id', $id)->get();
        } else {
                $access = DB::select('SELECT r.status,r.id,r.name,r.pid,rs.name as pname FROM brd_qx_access AS r INNER JOIN brd_qx_access AS rs ON rs.id = r.pid WHERE r.id =' . $id);
        }
        return $access;
    }

    /**
     * desc : 权限分配修改
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function accessUpdate($data)
    {
        $access = DB::table('qx_access')->where('id', $data['id'])
            ->update([
                'name' => $data['name'],
                'status' => $data['status']
            ]);
        if ($access == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
