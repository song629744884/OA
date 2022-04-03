<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    //
    public function login()
    {
        return view('index/login');
    }

    public function loginIn(Request $request)
    {
        if($request->method()=='POST'){
            $phone = $request->input('phone');
            $password = $request->input('password');
            if(empty($phone)||empty($password)){
                return response()->json(['code'=>0,'msg'=>'账号密码不得为空']);
            }
            if (Auth::attempt(['phone' => $phone, 'password' => $password])) {
                // 用户存在，
                return response()->json(['code'=>1,'msg'=>'登录成功']);
            }
            //$pwd = "123456";
            //$hash = password_hash($pwd, PASSWORD_DEFAULT);
            return response()->json(['code'=>0,'msg'=>'账号密码错误']);

        }
    }

    public function index()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '首页';
        $context['me'] = $me;
        return view('index/index',$context);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['code'=>1,'msg'=>'登出成功']);
    }

    public function imgUpload(Request $request)
    {
        $path = $request->file('file')->store('static/upload');

        return '/'.$path;
//        return storage_path('app/'.$path);
//        return Storage::url('app/'.$path);
//        return storage_path().'/'.$path;
    }
}
