<?php
namespace app\controller;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT as JWTUtil;
use Firebase\JWT\Key;
use think\Exception;

class JWT
{
    private static $key = 'song123456';
    /**
     * 根据json web token设置的规则生成token
     * @return \think\response\Json
     */
    static public function createjwt($userId)
    {
        $key = md5(self::$key); //jwt的签发密钥，验证token的时候需要用到
        $time = time(); //签发时间
        $expire = $time + 14400; //过期时间
        $token = array(
            "user_id" => $userId,
            "iss" => "http://www.tpoa.com/",//签发组织
            "aud" => "song", //签发作者
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expire
        );
        return JWTUtil::encode($token,$key,'HS256');
    }

    static function checkToken($token){
        $key = md5(self::$key);     //自定义的一个随机字串用户于加密中常用的 盐  salt
        $res['status'] = false;
        try {
            JWTUtil::$leeway    = 60;//当前时间减去60，把时间留点余地
            $decoded        = JWTUtil::decode($token, new Key($key, 'HS256')); //HS256方式，这里要和签发的时候对应
            $arr            = (array)$decoded;
            $res['status']  = 200;
            $res['data']    =(array)$arr['data'];
            return $res;

        } catch(\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            $res['info']    = "签名不正确";
            return $res;
        }catch(\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            $res['info']    = "token失效";
            return $res;
        }catch(\Firebase\JWT\ExpiredException $e) { // token过期
            $res['info']    = "token失效";
            return $res;
        }catch(Exception $e) { //其他错误
            $res['info']    = "未知错误";
            return $res;
        }
    }

    /**
     * 验证token
     * @return \think\response\Json
     */
    static public function verifyjwt($jwt)
    {
        //$jwt= input("jwt");
        $key = md5(self::$key); //jwt的签发密钥，验证token的时候需要用到
        try {
            $jwtAuth = json_encode(JWTUtil::decode($jwt, new Key($key, "HS256")));
            $authInfo = json_decode($jwtAuth, true);
            if (!$authInfo['user_id']) {
//                return json([
//                    'msg'=>'失败',
//                    'code'=>'600',
//                    'data'=>'',
//                ]);
                return false;
            }
            return true;
//            return json([
//                'msg'=>'OK',
//                'code'=>'200',
//                'data'=>'',
//            ]);
        } catch (ExpiredException $e) {
            throw new Exception('token过期');
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function getRequestToken()
    {
        if (empty($_SERVER['HTTP_AUTHORIZATION']))
        {
            return false;
        }
        $header = $_SERVER['HTTP_AUTHORIZATION'];
        $method = 'bearer';
//去除token中可能存在的bearer标识
        return trim(str_ireplace($method,'',$header));
    }

}