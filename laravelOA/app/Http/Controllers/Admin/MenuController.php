<?php

namespace App\Http\Controllers\Admin;

use App\Models\Common;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    //
    public function index()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '菜单管理';
        $context['me'] = $me;
        return view('menu/index',$context);
    }

    public function menuData(){
        $data = Menu::get();
        $menuMap = $data->keyBy('pid')->all();
        $data->each(function($item,$key)use($menuMap){
            $item['pid_str'] = $item['pid']==0?'顶级菜单':$menuMap[$item['pid']]['name'];
        });
        $topArr = Common::getTopData($data);
        Common::getLevelData($data,$topArr);


        return response()->json([
            'code' => 1,
            'msg' => 'get success',
            'data' => $topArr
        ]);
    }

    public function menuList()
    {
        $data = Menu::where('status',1)->get();
        $menus = Common::getTopMenu($data);
        Common::getLevelMenu($data,$menus);


        return response()->json($menus);
    }

    public function roleMenu()
    {
        $data = Menu::where('status',1)->get();
        $menus = Common::getTopMenu2($data);
        Common::getLevelMenu2($data,$menus);


        return response()->json([
            'code' => 1,
            'msg' => 'get success',
            'data' => $menus
        ]);
    }

    public function topMenu()
    {
        $data = Menu::where('pid',0)->get();
        return response()->json([
            'code' => 1,
            'msg' => 'get success',
            'data' => $data
        ]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'pid'=>'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'msg' => '校验失败'
            ]);
        }
        $id = $request->input('id');
        if(empty($id)){
            $model = new Menu();
        }else{
            $model = Menu::findOrFail($id);
        }
        $model->name = $request->input('name');
        $model->pid = $request->input('pid');
        $model->icon = $request->input('icon');
        $model->url = $request->input('url');
        $model->status = $request->input('status');
        $model->idx = $request->input('idx',0);
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
//        $id = $request->input('id');
        $model = Menu::findOrFail($id);
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
}
