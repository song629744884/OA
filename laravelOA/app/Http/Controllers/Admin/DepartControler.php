<?php

namespace App\Http\Controllers\Admin;

use App\Models\Depart;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartControler extends Controller
{
    //
    public function index()
    {
        $me = Auth::user();
        $context = [];
        $context['title'] = '部门管理';
        $context['me'] = $me;
        return view('depart/index',$context);
    }

    public function data()
    {
        $departs = Depart::get();
        $top_departs = Depart::where('pid',0)->get();
        $departDict = User::selectRaw('depart,count(depart) as ct')->groupBy('depart')->whereNotNull('depart')->pluck('ct','depart');

        foreach ($departs as $k => $v){
            $departs[$k]->count = $departDict[$v->id]??0;
        }
        //树
        $data = $this->handledata($departs,0);
        $res = [
            'data'=>$data,
            'menuSelectList'=>$top_departs
        ];
        return response()->json($res);
    }

    public function handledata($data,$pid)
    {
        $lst = [];
        foreach ($data as $k => $v){
            if($v->pid==$pid){
                array_push($lst,$v);
                $v->children = $this->handledata($data,$v->id);
            }

        }
        return $lst;
    }

    public function save(Request $request)
    {
        //
        if($request->method()=='POST'){
            $id = $request->input('id');
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'pid'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'msg' => '校验失败'
                ]);
            }
            if(empty($id)){
                $departObj = new Depart();
                $departObj->name = $request->input('name');
                $departObj->pid = $request->input('pid');
                $departObj->intro = $request->input('intro');
                $departObj->save();
                return response()->json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $departObj = Depart::find($id);
                $departObj->name = $request->input('name');
                $departObj->pid = $request->input('pid');
                $departObj->intro = $request->input('intro');
                $departObj->save();
                return response()->json(['code'=>1,'msg'=>'修改成功']);
            }
        }
    }

    public function delete($id)
    {
        $departObj = Depart::find($id);
        $departObj->delete();
        return response()->json(['code'=>1,'msg'=>'删除成功']);
    }

    public function option()
    {
        $departs = Depart::all();
        $data = $this->handleOption($departs,0);
        return response()->json($data);
    }

    public function handleOption($data,$pid)
    {
        $lst = [];
        foreach($data as $k => $v){
            if($v->pid==$pid){
                $v->label = $v->name;
                $v->value = $v->id;
                if($this->handleOption($data,$v->id)){
                    $v->children = $this->handleOption($data,$v->id);
                }
                $lst[] = $v;
            }
        }
        return $lst;
    }
}
