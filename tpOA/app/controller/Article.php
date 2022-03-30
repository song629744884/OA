<?php
declare (strict_types = 1);

namespace app\controller;

use app\validate\ArticleValidate;
use think\exception\ValidateException;
use think\Request;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class Article
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function all()
    {
        //
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '文档列表';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('article',$context);
    }

    public function allData(Request $request)
    {
        $page_num = $request->param('page_num');
        $currentPage = $request->param('currentPage');
        $currentPage = $currentPage==0?1:$currentPage;
        $data = \app\model\Article::with(['type','user'])->order('created_at','desc')->page((int)$currentPage,(int)$page_num)->select();
        $total = \app\model\Article::count();
        return json(['data'=>$data,'total'=>$total]);
    }

    public function view(Request $request)
    {
        $id = $request->param('id');
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '查看文档';
        $context['me'] = $me;
        $context['id'] = $id;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('article_view',$context);
    }

    public function index()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '我的文档';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('myArticle',$context);
    }

    public function data(Request $request)
    {
        $page_num = $request->param('page_num');
        $currentPage = $request->param('currentPage');
        $phone = Session::get('phone');
        $currentPage = $currentPage==0?1:$currentPage;
        $data = \app\model\Article::alias('a')->with(['type','user'])->field('a.*')->join('user u','a.user_id=u.id')->order('a.created_at','desc')->page((int)$currentPage,(int)$page_num)->where('u.phone',$phone)->select();
        $total = \app\model\Article::count();
        return json(['data'=>$data,'total'=>$total]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '文档添加';
        $context['me'] = $me;
        $context['id'] = '';
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('article_form',$context);
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
            $id = $request->param('id');
            try{
                validate(ArticleValidate::class)->check($request->param());
            }catch (ValidateException $e){
                return json(['code'=>0,'msg'=>$e->getError()]);
            }
            if(empty($id)){
                $phone = Session::get('phone');
                $me = \app\model\User::where('phone',$phone)->find();
                $obj = new \app\model\Article();
                $obj->title = $request->param('title');
                $obj->content = $request->param('content');
                $obj->type_id = $request->param('type_id');
                $obj->pic = $request->param('pic');
                $obj->user_id = $me['id'];
                $obj->created_at = date('Y-m-d H:i:s');
                $obj->updated_at = date('Y-m-d H:i:s');
                $obj->save();
                return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $obj = \app\model\Article::find($id);
                $obj->title = $request->param('title');
                $obj->content = $request->param('content');
                $obj->type_id = $request->param('type_id');
                $obj->pic = $request->param('pic');
                $obj->updated_at = date('Y-m-d H:i:s');
                $obj->save();
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
    public function read(Request $request)
    {
        //
        $id = $request->param('id');
        $data = \app\model\Article::find($id);
        return json(['code'=>1,'data'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        //
        $id = $request->param('id');
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '文档修改';
        $context['me'] = $me;
        $context['id'] = $id;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('article_form',$context);
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
        $obj = \app\model\Article::find($id);
        $obj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }
}
