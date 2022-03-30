<?php
declare (strict_types = 1);

namespace app\controller;

use app\model\User;
use app\validate\RoleValidate;
use think\exception\ValidateException;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;
use think\Request;

class Role
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
        return View::fetch('role',$context);
    }

    //列表数据
    public function data()
    {
        $data = \app\model\Role::select();
        return json($data);
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
        //
        if($request->method()=='POST'){
            $id = $request->post('id');
            try{
                validate(RoleValidate::class)->check($request->param());
            }catch (ValidateException $e){
                return json(['code'=>0,'msg'=>$e->getError()]);
            }
            if(empty($id)){
               $roleObj = new \app\model\Role();
               $roleObj->name = $request->param('name');
               $roleObj->menus = $request->param('menus');
               $roleObj->save();
               return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $roleObj = \app\model\Role::find($id);
                $roleObj->name = $request->param('name');
                $roleObj->menus = $request->param('menus');
                $roleObj->save();
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
        $roleObj = \app\model\Role::find($id);
        $roleObj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }

    //角色菜单
    public function menus(Request $request)
    {
        $id = $request->param('id');
        $menus = \app\model\Role::where('id',$id)->value('menus');
        return json($menus);
    }

    //角色菜单保存
    public function menuSave(Request $request)
    {
        $role_id = $request->param('role_id');
        $node = $request->param('node');
        $roleObj = \app\model\Role::find($role_id);
        $roleObj->menus = $node;
        $roleObj->save();
        return json(['code'=>1,'msg'=>'保存成功']);
    }
}
