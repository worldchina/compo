<?php
namespace xiang\rbac;
use DB;
class Node
{
    /**
     * desc : 权限展示
     * auth : weiyang
     * time : 2017/2/9 14:45
     * @return array
     */
    public function nodeShow()
    {
        $data = DB::table('qx_node')->get();
        $node = $this->nodeShows($data,$id=0,$level=0);
        return $node;
    }
    /**
     * 递归
     * @param $data
     * @param int $id
     * @param int $level
     * @return array
     */
    private function nodeShows($data,$id=0,$level = 0)
    {

        static $arr = array();
        foreach($data as $key=>$v){
            if($v['pid']==$id){
                $v['level'] = $level;
                $arr[] = $v;
                $this->nodeShows($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    /**
     * desc : 权限条件搜索
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function nodeSelect($data)
    {
        $node = DB::table('qx_node')
            ->where('title', 'like', '%' . $data['title'] . '%')
            ->where('name', 'like', '%' . $data['name'] . '%')
            ->get();
        if (empty($node)) {
            return 0;
        } else {
            return $node;
        }
    }

    /**
     * desc : 权限添加页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @return mixed
     */
    public function nodeFrom()
    {
        $node = DB::table('qx_node')
            ->where('pid', 0)
            ->where('status', 1)
            ->get();
        return $node;
    }

    /**
     * desc : 权限添加
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function nodeInsert($data)
    {
        $node = DB::table('qx_node')->insert(
            [
                'name' => $data['name'],
                'pid' => $data['pid'],
                'title' => $data['title'],
                'sort' => $data['sort'],
                'status' => $data['status']
            ]
        );
        if ($node == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 权限删除
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return int
     */
    public function nodeDelete($id)
    {
        $node = DB::table('qx_node')
            ->where('pid', $id)
            ->get();
        if (!empty($node)) {
            for ($i = 0; $i < count($node); $i++) {
                DB::table('qx_node')->where('id', $node[$i]['id'])
                    ->delete();
            }
        }
        $delate = DB::table('qx_node')->where('id', $id)
            ->delete();
        if ($delate == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * desc : 权限修改页面
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $id
     * @return mixed
     */
    public function nodeFroms($id)
    {
        $pid = DB::table('qx_node')
            ->where('id', $id)
            ->get();
        if ($pid[0]['pid'] == 0) {
            $node = DB::table('qx_node')->where('id', $id)->get();
        } else {
            $node = DB::select('SELECT r.sort,r.title,r.status,r.id,r.name,r.pid,rs.title as ptitle FROM brd_qx_node AS r INNER JOIN brd_qx_node AS rs ON rs.id = r.pid WHERE r.id =' . $id);
        }
        return $node;
    }

    /**
     * desc : 权限修改
     * auth : weiyang
     * time : 2017/2/9 16:20
     * @param $data
     * @return int
     */
    public function nodeUpdate($data)
    {
        $node = DB::table('qx_node')->where('id', $data['id'])
            ->update([
                'title' => $data['title'],
                'name' => $data['name'],
                'sort' => $data['sort'],
                'status' => $data['status']
            ]);
        if ($node == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
