<?php
declare (strict_types = 1);

namespace app\middleware;

use app\controller\JWT;
use think\facade\Cookie;

class CsrfToken
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
        if($request->method()=='POST'){
            $token = Cookie::get('csrftoken');
            $res = JWT::checkToken($token);
            if($res['status']==200){
                return $next($request);
            }else{
                throw new Exception($res['info']);
            }
        }
        return $next($request);
    }
}
