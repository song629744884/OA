<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Session;

class UserCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
        if(Session::has('phone')){
            return $next($request);
        }else{
            return redirect((string) url('index/login'));
        }
    }
}
