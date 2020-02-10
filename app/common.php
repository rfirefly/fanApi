<?php
// 应用公共文件
function ApiException($msg='出现错误', $errorCode = '1000', $code = 400)
{
    throw new \app\lib\BaseException(['message' => $msg, 'errorCode' => $errorCode, 'code' => $code]);
}

// 获取文件完整url
function getFileUrl($path = '', $addSlash = false)
{
    if (!$path) return;
    return (string)\url(($addSlash ? '/'.$path : $path), [], false, true);
}