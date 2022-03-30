<?php
declare (strict_types = 1);

namespace app\controller;

use app\validate\DepartValidate;
use think\exception\ValidateException;
use think\Request;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class Depart
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $phone = Session::get('phone');
        $me = \app\model\User::where('phone',$phone)->find();
        $context = [];
        $context['title'] = '部门管理';
        $context['me'] = $me;
        $csrfToken = JWT::createjwt($me['id']);
        Cookie::set('csrftoken',$csrfToken,3600);
        return View::fetch('depart',$context);
    }

    public function data()
    {
        $departs = \app\model\Depart::select();
        $top_departs = \app\model\Depart::where('pid',0)->select();
//        $departDict = \app\model\User::fieldRaw('depart,count(depart) as ct')->group('depart')->column('count(depart)','depart');
        $departDict = \app\model\User::fieldRaw('depart,count(depart) as ct')->group('depart')->where('depart','not null')->select();
        $departDict = array_column($departDict->toArray(),'ct','depart');
        //return $departDict;
        foreach ($departs as $k => $v){
            $departs[$k]['count'] = $departDict[$v['id']]??0;
        }
        //树
        $data = $this->handledata($departs,0);
        $res = [
            'data'=>$data,
            'menuSelectList'=>$top_departs
        ];
        return json($res);
    }

    public function handledata($data,$pid)
    {
        $lst = [];
        foreach ($data as $k => $v){
            if($v['pid']==$pid){
                array_push($lst,$v);
                $v['children'] = $this->handledata($data,$v['id']);
            }

        }
        return $lst;
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
                validate(DepartValidate::class)->check($request->param());
            }catch (ValidateException $e){
                return json(['code'=>0,'msg'=>$e->getError()]);
            }
            if(empty($id)){
                $departObj = new \app\model\Depart();
                $departObj->name = $request->param('name');
                $departObj->pid = $request->param('pid');
                $departObj->intro = $request->param('intro');
                $departObj->save();
                return json(['code'=>1,'msg'=>'添加成功']);
            }else{
                $departObj = \app\model\Depart::find($id);
                $departObj->name = $request->param('name');
                $departObj->pid = $request->param('pid');
                $departObj->intro = $request->param('intro');
                $departObj->save();
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
        $departObj = \app\model\Depart::find($id);
        $departObj->delete();
        return json(['code'=>1,'msg'=>'删除成功']);
    }

    public function option()
    {
        $departs = \app\model\Depart::select();
        $data = $this->handleOption($departs,0);
        return json($data);
    }

    public function handleOption($data,$pid)
    {
        $lst = [];
        foreach($data as $k => $v){
            if($v['pid']==$pid){
                $v['label'] = $v['name'];
                $v['value'] = $v['id'];
                if($this->handleOption($data,$v['id'])){
                    $v['children'] = $this->handleOption($data,$v['id']);
                }
                $lst[] = $v;
            }
        }
        return $lst;
    }
}
