<?php
declare (strict_types = 1);

namespace app\controller;

use app\model\Role;
use app\model\User;
use app\validate\MenuValidate;
use think\exception\ValidateException;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;
use think\Request;

class Menu
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $phone = Session::get('phone');
        $me = User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '菜单管理';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('menu',$context);
    }

    //菜单列表数据
    public function menuData()
    {
        $menus = \app\model\Menu::order('idx','asc')->select()->append(['status_str']);
        $rlist = [];
        $rlist = $this->handleRec($menus,0,$rlist);
        $selectMenus = \app\model\Menu::order('idx','asc')->where('status',1)->where('pid',0)->select();
        return json(['menu'=>$rlist,'menuSelectList'=>$selectMenus]);
    }

    //角色菜单数据
    public function roleMenuData()
    {
        $data = \app\model\Menu::order('idx','asc')->select();
        $rlist = [];
        $rlist = $this->handleRec($data,0,$rlist);
        return json($rlist);
    }

    public function handleRec($data,$pid=0,$rlist = [])
    {
        foreach ($data as $k => $v){
            if($v['pid']==$pid){
                $v['label'] = $v['name'];
                array_push($rlist,$v);
                $v['children'] = [];
                $v['children'] = $this->handleRec($data,$v['id'],$v['children']);

            }
        }
        return $rlist;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if($request->method()=='POST'){
            $id = $request->post('id');
            if(empty($id)){
                try{
                    validate(MenuValidate::class)->check($request->param());
                }catch (ValidateException  $e){
                    return json(['code'=>0,'msg'=>$e->getError()]);
                }
                \app\model\Menu::create($request->param());
                return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                try{
                    validate(MenuValidate::class)->check($request->param());
                }catch (ValidateException  $e){
                    return json(['code'=>0,'msg'=>$e->getError()]);
                }
                $menuObj = \app\model\Menu::findOrFail($id);
                $menuObj->pid = $request->post('pid');
                $menuObj->icon = $request->post('icon');
                $menuObj->name = $request->post('name');
                $menuObj->url = $request->post('url');
                $menuObj->status = $request->post('status');
                $menuObj->idx = $request->post('idx');
                $menuObj->save();
                return json(['code'=>1,'msg'=>'修改成功']);
            }
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $menuObj = \app\model\Menu::find($id);
        $menuObj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }

    public function menuList()
    {
        $phone = Session::get('phone');
        $roleId = User::where('phone',$phone)->value('role_id');
        $roleMenus = Role::where('id',$roleId)->value('menus');
        $roleMenuArr = explode(',',$roleMenus);
        $menus = \app\model\Menu::order('idx','asc')->select();
        $listMenus = [];
        foreach($menus as $k => $v){
            if(in_array($v['id'],$roleMenuArr)){
                array_push($listMenus,$v);
            }
        }
        $menuData = $this->handleMenu($listMenus);
        return json($menuData);
    }

    protected function handleMenu($listMenus,$pid=0,$index_str='')
    {
        $list1 = [];
        $index = 0;
        foreach($listMenus as $k => $v){
            $index += 1;
            if($v['pid']==$pid){
                $v['index'] = $index_str==''?(string)$index:$index_str.'-'.$index;
                $v['child'] = $this->handleMenu($listMenus,$v['id'],$v['index']);
                array_push($list1,$v);
            }
        }
        return $list1;
    }
}
