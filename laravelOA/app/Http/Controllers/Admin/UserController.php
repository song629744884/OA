<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(Request $request){
        $me = Auth::user();
        $context = [];
        $context['title'] = '员工管理';
        $context['me'] = $me;
        return view('user/index',$context);
    }

    public function data(Request $request){
        $page_num = $request->input('page_num');
        $currentPage = $request->input('currentPage');
        if($currentPage==0){
            $currentPage=1;
        }
        $start = ($currentPage-1)*$page_num;
        $list = User::with('departStr')->offset($start)->limit($page_num)->get();
        $total = User::count();
        return response()->json(['data'=>$list,'total'=>$total]);
    }

    public function save(Request $request){
        if($request->method()=='POST'){
            $id = $request->input('id');
            $phone =$request->input('phone');
            if(!preg_match("/^1[34578]\d{9}$/", $phone)) {
                return response()->json(['code'=>0,'msg'=>'手机号格式有误']);
            }
            if(!$this->check($phone,$id)){
                return response()->json(['code'=>0,'msg'=>'手机号已存在']);
            }
            if(empty($id)) {
                $validator = Validator::make($request->all(), [
                    'phone' => 'required',
                    'name' => 'required',
                    'sex'=>'required',
                    'status'=>'required',
                    'role_id'=>'required',
                    'birth'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['code'=>0,'msg'=>$validator->errors()->all()[0]]);
                }
                $userObj = new User();
                $userObj->name = $request->input('name');
                $userObj->code = 'qh_'.rand(100000,999999);
                $userObj->sex = $request->input('sex');
                $userObj->birth = $request->input('birth');
                $userObj->depart = $request->input('depart');
                $userObj->role_id = $request->input('role_id');
                $userObj->phone = $request->input('phone');
                $userObj->created_at = date('Y-m-d H:i:s');
                $userObj->updated_at = date('Y-m-d H:i:s');
                $userObj->status = $request->input('status');
                $userObj->password = password_hash('123456', PASSWORD_DEFAULT);
                $userObj->save();
                return response()->json(['code'=>1,'msg'=>'success']);


            }else{
                $validator = Validator::make($request->all(), [
                    'phone' => 'required',
                    'name' => 'required',
                    'sex'=>'required',
                    'status'=>'required',
                    'role_id'=>'required',
                    'birth'=>'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['code'=>0,'msg'=>$validator->errors()->all()[0]]);
                }
                $userObj = User::find($id);
                $userObj->name = $request->input('name');
                $userObj->sex = $request->input('sex');
                $userObj->birth = $request->input('birth');
                $userObj->depart = $request->input('depart');
                $userObj->role_id = $request->input('role_id');
                $userObj->phone = $request->input('phone');
                $userObj->status = $request->input('status');
                $userObj->updated_at = date('Y-m-d H:i:s');
                $userObj->save();
                return response()->json(['code'=>1,'msg'=>'success']);
            }

        }
    }

    //校验手机号码
    protected function check($phone,$id=''){
        if(empty($id)){
            if(User::where('phone',$phone)->first()){
                return false;
            }else{
                return true;
            }
        }else{
            if(User::where('phone',$phone)->where('id','<>',$id)->first()){
                return false;
            }else{
                return true;
            }
        }
    }

    public function delete($id)
    {
        $userObj = User::find($id);
        $userObj->delete();
        return response()->json(['code'=>1,'msg'=>'删除成功']);
    }

    public function me()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '个人信息';
        $context['me'] = $me;
        return view('user/me',$context);
    }

    public function myUserInfo()
    {
        $me = Auth::user();
//        $me->pic && $me->pic = Storage::url($me->pic);
        return response()->json(['code'=>1,'data'=>$me]);
    }

    public function saveMyUserInfo(Request $request)
    {
        if($request->method()=='POST'){
            $phone = $request->input('phone');
            $name = $request->input('name');
            $sex = $request->input('sex');
            $birth = $request->input('birth');
            $pic = $request->input('pic');
            if(!preg_match("/^1[34578]\d{9}$/", $phone)) {
                return response()->json(['code'=>0,'msg'=>'手机号格式有误']);
            }
            if($name==''){
                return response()->json(['code'=>0,'msg'=>'姓名不能为空']);
            }
            if($sex==''){
                return response()->json(['code'=>0,'msg'=>'请选择性别']);
            }
            if($birth==''){
                return response()->json(['code'=>0,'msg'=>'请选择出生日期']);
            }
            $me = Auth::user();
            $obj = User::where('phone',$me['phone'])->first();
            $obj->name = $name;
            $obj->sex= $sex;
            $obj->birth= $birth;
            $obj->pic= $pic;
            $obj->save();
            return response()->json(['code'=>1,'msg'=>'修改成功']);
        }
    }

    public function password()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '修改密码';
        $context['me'] = $me;
        return view('user/password',$context);
    }

    public function passwordSave(Request $request)
    {
        if($request->method()=='POST'){
            $me = Auth::user();
            $password = $request->input('password');
            $npassword = $request->input('npassword');
            $rpassword = $request->input('rpassword');
            if($npassword!=$rpassword){
                return response()->json(['code'=>0,'msg'=>'新密码与重复密码不一致']);
            }
            if (Auth::attempt(['phone' => $me['phone'], 'password' => $password])) {
                // 用户存在，
                $obj = User::where('phone',$me['phone'])->first();
                $obj->password = password_hash($npassword, PASSWORD_DEFAULT);
                $obj->save();
                return response()->json(['code'=>1,'msg'=>'密码修改成功']);
            }else{
                return response()->json(['code'=>0,'msg'=>'密码错误']);
            }
        }
    }
}
