<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Cache;

class ApiUserAuth
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
        // 获取头部信息
        $param = $request->header();
        // 不含token
        if (!array_key_exists('token',$param)) \ApiException('非法操作, 请先登入', 40003, 200);
        // 当前用户token是否存在（是否登录）
        $token = $param['token'];
        $user = Cache::get($token);
        // 验证失败（未登录或已过期）
        if(!$user) \ApiException('已退出, 请重新登入', 40004, 200);
        $request->userToken = $token;
        $request->userId = array_key_exists('type',$user) ? $user['user_id'] : $user['id'];
        $request->userInfo = $user;
        return $next($request);
    }
}
