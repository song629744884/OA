<?php
declare (strict_types = 1);

namespace app\controller;

use app\validate\ArticleTypeValidate;
use think\exception\ValidateException;
use think\Request;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class ArticleType
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '文档分类';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('article_type',$context);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

    }

    public function data()
    {
        $data = \app\model\ArticleType::select();
        return json($data);
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
                validate(ArticleTypeValidate::class)->check($request->param());
            }catch (ValidateException $e){
                return json(['code'=>0,'msg'=>$e->getError()]);
            }
            if(empty($id)){
                $obj = new \app\model\ArticleType();
                $obj->name = $request->param('name');
                $obj->save();
                return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $obj = \app\model\ArticleType::find($id);
                $obj->name = $request->param('name');
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
        $obj = \app\model\ArticleType::find($id);
        $obj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }
}
