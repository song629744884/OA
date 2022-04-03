<?php

namespace App\Http\Controllers\Admin;

use App\Models\ArticleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleTypeControler extends Controller
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $me = Auth::user();
        $context = [];
        $context['title'] = '文档分类';
        $context['me'] = $me;
        return view('article_type/index',$context);
    }

    public function data()
    {
        $data = ArticleType::all();
        return response()->json($data);
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
                'name'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'msg' => '校验失败'
                ]);
            }

            if(empty($id)){
                $obj = new ArticleType();
                $obj->name = $request->input('name');
                $obj->save();
                return response()->json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $obj = ArticleType::find($id);
                $obj->name = $request->input('name');
                $obj->save();
                return response()->json(['code'=>1,'msg'=>'修改成功']);
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $obj = ArticleType::find($id);
        $obj->delete();
        return response()->json(['code'=>1,'msg'=>'删除成功']);
    }
}
