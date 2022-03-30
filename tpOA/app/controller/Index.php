<?php
namespace app\controller;

use app\BaseController;
use app\model\User;
use app\Request;
use think\facade\Cookie;
use think\facade\Filesystem;
use think\facade\Session;
use think\facade\View;

class Index extends BaseController
{
//    public function index()
//    {
//        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
//    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }


    public function login()
    {
        return View::fetch('login');
    }

    public function loginIn(Request $request)
    {
        if($request->method()=='POST'){
            $phone = $request->param('phone');
            $password = $request->param('password');
            if(empty($phone)||empty($password)){
                return json(['code'=>0,'msg'=>'账号密码不得为空']);
            }
            $res = User::where('phone',$phone)->where('password',md5($password))->find();
            if(!empty($res)){
                Session::set('phone',$res['phone']);
                return json(['code'=>1,'msg'=>'登录成功','res'=>$res['phone']]);
            }else{
                return json(['code'=>0,'msg'=>'账号密码错误']);
            }
        }
    }

    public function index()
    {

        $me = User::where('phone',Session::get('phone'))->find();
        $context = [];
        $context['title'] = '首页';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('index',$context);
    }

    public function logout()
    {
        Session::delete('phone');
        return json(['code'=>1,'msg'=>'登出成功']);
//        return redirect('login');
    }

    public function imgUpload(Request $request)
    {
        $file = $request->file('file');
        $savename = Filesystem::putFile('upload',$file);
        return $savename;
    }
}
