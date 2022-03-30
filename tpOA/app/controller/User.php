<?php
declare (strict_types = 1);

namespace app\controller;

use app\validate\UserValidate;
use think\exception\ValidateException;
use think\Request;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class User
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '员工管理';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('user',$context);
    }


    public function data(Request $request)
    {
        $page_num = $request->param('page_num');
        $currentPage = $request->param('currentPage');
        $currentPage = $currentPage==0?1:$currentPage;
        $data = \app\model\User::with('departStr')->page((int)$currentPage,(int)$page_num)->select()->append(['sex_str','status_str']);
        $total = \app\model\User::count();
        return json([
            'data'=>$data,
            'total'=>$total
        ]);
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
            $id = $request->param('id');
            $phone = $request->param('phone');
            try{
                validate(UserValidate::class)->check($request->param());
            }catch (ValidateException $e){
                return json(['code'=>0,'msg'=>$e->getError()]);
            }
            if(!preg_match("/^1[34578]\d{9}$/", $phone)) {
                return json(['code'=>0,'msg'=>'手机号格式有误']);
            }
            if(!$this->check($phone,$id)){
                return json(['code'=>0,'msg'=>'手机号已存在']);
            }
            if(empty($id)){
                $userObj = new \app\model\User();
                $userObj->name = $request->param('name');
                $userObj->code = 'qh_'.rand(100000,999999);
                $userObj->sex = $request->param('sex');
                $userObj->birth = $request->param('birth');
                $userObj->depart = $request->param('depart');
                $userObj->role_id = $request->param('role_id');
                $userObj->phone = $request->param('phone');
                $userObj->created_at = date('Y-m-d H:i:s');
                $userObj->updated_at = date('Y-m-d H:i:s');
                $userObj->status = $request->param('status');
                $userObj->password = md5('123456');
                $userObj->save();
                return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $userObj = \app\model\User::find($id);
                $userObj->name = $request->param('name');
                $userObj->sex = $request->param('sex');
                $userObj->birth = $request->param('birth');
                $userObj->depart = $request->param('depart');
                $userObj->role_id = $request->param('role_id');
                $userObj->phone = $request->param('phone');
                $userObj->status = $request->param('status');
                $userObj->updated_at = date('Y-m-d H:i:s');
                $userObj->save();
                return json(['code'=>1,'msg'=>'修改成功']);
            }
        }
    }

    //校验手机号码
    protected function check($phone,$id=''){
        //if(preg_match("/^1[34578]\d{9}$/", $phone)) {
            if(empty($id)){
                if(\app\model\User::where('phone',$phone)->find()){
                    return false;
                }else{
                    return true;
                }
            }else{
                if(\app\model\User::where('phone',$phone)->where('id','<>',$id)->find()){
                    return false;
                }else{
                    return true;
                }
            }
        //}
        //return false;
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
        $userObj = \app\model\User::find($id);
        $userObj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }

    public function me()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '个人信息';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('me',$context);
    }

    public function myUserInfo()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        return json(['code'=>1,'data'=>$me]);
    }

    public function saveMyUserInfo(Request $request)
    {
        if($request->method()=='POST'){
            $phone = $request->param('phone');
            $name = $request->param('name');
            $sex = $request->param('sex');
            $birth = $request->param('birth');
            $pic = $request->param('pic');
            if(!preg_match("/^1[34578]\d{9}$/", $phone)) {
                return json(['code'=>0,'msg'=>'手机号格式有误']);
            }
            if($name==''){
                return json(['code'=>0,'msg'=>'姓名不能为空']);
            }
            if($sex==''){
                return json(['code'=>0,'msg'=>'请选择性别']);
            }
            if($birth==''){
                return json(['code'=>0,'msg'=>'请选择出生日期']);
            }
            $obj = \app\model\User::where('phone',Session::get('phone'))->find();
            $obj->name = $name;
            $obj->sex= $sex;
            $obj->birth= $birth;
            $obj->pic= $pic;
            $obj->save();
            return json(['code'=>1,'msg'=>'修改成功']);
        }
    }

    public function password()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '修改密码';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('password',$context);
    }

    public function passwordSave(Request $request)
    {
        if($request->method()=='POST'){
            $phone = Session::get('phone');
            $password = $request->param('password');
            $npassword = $request->param('npassword');
            $rpassword = $request->param('rpassword');
            if($npassword!=$rpassword){
                return json(['code'=>0,'msg'=>'新密码与重复密码不一致']);
            }
            $obj = \app\model\User::where('phone',$phone)->where('password',md5($password))->find();

            if($obj){
                $obj->password = md5($npassword);
                $obj->save();
                return json(['code'=>1,'msg'=>'密码修改成功']);
            }else{
                return json(['code'=>0,'msg'=>'密码错误']);
            }
        }
    }
}
