<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    //
    public function index()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '菜单管理';
        $context['me'] = $me;
        return view('role/index',$context);
    }

    public function data()
    {
        $data = Role::get();
        return response()->json($data);
    }



    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'msg' => '校验失败'
            ]);
        }
        $id = $request->input('id');
        if(empty($id)){
            $model = new Role();
        }else{
            $model = Role::findOrFail($id);
        }
        $model->name = $request->input('name');
        if($model->save()){
            $code =1;
            $msg = '新增修改成功';
        }else{
            $code = 0;
            $msg = '新增修改失败';
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg
        ]);
    }

    public function delete($id){
        $model = Role::findOrFail($id);
        if($model->delete()){
            return response()->json([
                'code' => 1,
                'msg' => '删除成功'
            ]);
        }
        return response()->json([
            'code' => 0,
            'msg' => '删除失败'
        ]);
    }


    public function menus($id)
    {
        $data = Role::where('id',$id)->value('menus');

        return response()->json($data);
    }

    public function saveNode(Request $request)
    {
        $id = $request->input('id');
        $node = $request->input('node');
        $record = Role::where('id',$id)->first();
        $record->menus = $node;
        $record->save();

        return response()->json([
            'code' => 1,
            'msg' => '保存成功'
        ]);
    }
}
