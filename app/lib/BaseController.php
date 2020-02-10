<?php
declare (strict_types = 1);

namespace app\lib;

class BaseController
{
    static public function resCode($msg = '无数据', $data = [], $code = 200)
    {
        $res = [
            'msg'=>$msg,
            'data'=>$data
        ];
        return json($res, $code);
    }

    static public function resCodeWithoutData($msg = '无数据', $code = 200)
    {
        return self::resCode($msg, [], $code);
    }
}




