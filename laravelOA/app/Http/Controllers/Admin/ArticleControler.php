<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleControler extends Controller
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function all()
    {
        //
        $me = Auth::user();
        $context = [];
        $context['title'] = '文档列表';
        $context['me'] = $me;
        return view('article/article',$context);
    }

    public function allData(Request $request)
    {
        $page_num = $request->input('page_num');
        $currentPage = $request->input('currentPage');
        $currentPage = $currentPage==0?1:$currentPage;
        $start = ($currentPage-1)*$page_num;
        $data = Article::with(['type','user'])->orderBy('created_at','desc')->offset($start)->limit($page_num)->get();
        $total = Article::count();
        return response()->json(['data'=>$data,'total'=>$total]);
    }

    public function view(Request $request)
    {
        $id = $request->input('id');
        $me = Auth::user();
        $context = [];
        $context['title'] = '查看文档';
        $context['me'] = $me;
        $context['id'] = $id;
        return view('article/article_view',$context);
    }

    public function index()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '我的文档';
        $context['me'] = $me;
        return view('article/index',$context);
    }

    public function data(Request $request)
    {
        $page_num = $request->input('page_num');
        $currentPage = $request->input('currentPage');
        $me = Auth::user();
        $phone = $me['phone'];
        $currentPage = $currentPage==0?1:$currentPage;
        $start = ($currentPage-1)*$page_num;
        $data = Article::with(['type','user'])->select('article.*')->join('user','article.user_id','=','user.id')->orderBy('article.created_at','desc')->offset($start)->limit($page_num)->where('user.phone',$phone)->get();
        $total = Article::count();
        return response()->json(['data'=>$data,'total'=>$total]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $me = Auth::user();
        $context = [];
        $context['title'] = '文档添加';
        $context['me'] = $me;
        $context['id'] = '';
        return view('article/article_form',$context);
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
            $id = $request->input('id');
            $validator = Validator::make($request->all(), [
                'title'  =>  'required',
                'content'  =>  'required',
                'type_id'  =>  'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'msg' => '校验失败'
                ]);
            }
            if(empty($id)){
                $me = Auth::user();
                $obj = new Article();
                $obj->title = $request->input('title');
                $obj->content = $request->input('content');
                $obj->type_id = $request->input('type_id');
                $obj->pic = $request->input('pic');
                $obj->user_id = $me['id'];
                $obj->created_at = date('Y-m-d H:i:s');
                $obj->updated_at = date('Y-m-d H:i:s');
                $obj->save();
                return response()->json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $obj = Article::find($id);
                $obj->title = $request->input('title');
                $obj->content = $request->input('content');
                $obj->type_id = $request->input('type_id');
                $obj->pic = $request->input('pic');
                $obj->updated_at = date('Y-m-d H:i:s');
                $obj->save();
                return response()->json(['code'=>1,'msg'=>'修改成功']);
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
        $id = $request->input('id');
        $data = Article::find($id);
        return response()->json(['code'=>1,'data'=>$data]);
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
        $id = $request->input('id');
        $me = Auth::user();
        $context = [];
        $context['title'] = '文档修改';
        $context['me'] = $me;
        $context['id'] = $id;
        return view('article/article_form',$context);

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
        $obj = Article::find($id);
        $obj->delete();
        return response()->json(['code'=>1,'msg'=>'删除成功']);
    }
}
