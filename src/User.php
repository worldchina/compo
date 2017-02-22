<?php
namespace Rbac;
use DB;
class User
{
    /**
     * desc : 用户展示
     * auth : weiyang
     * time : 2017/2/9 14:45
     * @return mixed
     */
    public function userShow()
    {
        $user = DB::table('qx_user')->simplePaginate(15);
        return $user;
    }

    /**
     * desc : 用户条件搜索
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function userSelect($data)
    {
        $user = DB::table('qx_user')
            ->where('name', 'like', '%' . $data['name'] . '%')
            ->where('email', 'like', '%' . $data['email'] . '%')
            ->get();
        if (empty($user)) {
            return 0;
        } else {
            return $user;
        }
    }


    /**
     * desc : 用户添加
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function userInsert($data)
    {
        $user = DB::table('qx_user')->insert(
            [
                'name' => $data['name'],
                'password' => $data['password'],
                'email' => $data['email'],
                'create_time' => date('Y-m-d H:i:s'),
                'logintime' => date('Y-m-d H:i:s'),
                'status' => $data['status']
            ]
        );
        if ($user==1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 用户删除
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return int
     */
    public function userDelete($id)
    {
        $user = DB::table('qx_user')->where('id', $id)
            ->delete();
        if ($user==1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 用户修改页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return mixed
     */
    public function userFroms($id)
    {
        $user = DB::table('qx_user')->where('id',$id)
            ->get();
        return $user;
    }

    /**
     * desc : 用户修改
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function userUpdate($data)
    {
        $user = DB::table('qx_user')->where('id', $data['id'])
            ->update([
                'name' => $data['name'],
                'password' => $data['password'],
                'email' => $data['email'],
                'status' => $data['status']
            ]);
        if ($user==1) {
            return 1;
        } else {
            return 0;
        }
    }


}

?>