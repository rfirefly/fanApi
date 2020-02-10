<?php
declare (strict_types = 1);

namespace app\middleware;

use \app\model\User;

class ApiUserBindPhone
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
        $param = $request->userInfo;
        (new User()) -> isBindPhone($param);
        return $next($request);
    }
}
