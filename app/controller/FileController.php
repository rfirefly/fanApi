<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;

class FileController
{
    public static function upload($inputName, $files,$path = 'uploads', $size = '2067800',$ext = 'jpg,png,gif'){
        // 未传入文件
        if(!$files) return ['isUpload'=>false,'msg'=>'没有文件, 请选择'];

        try{
            // 文件验证
            validate([$inputName => [
                'fileSize' => $size,
                'fileExt'  => $ext
            ]])->check([$inputName => $files]);
            // 多文件处理
            if (is_array($files)) {
                $savename = [];
                foreach($files as $file) {
                    $savename[] = \think\facade\Filesystem::disk('uploads')->putFile( $path, $file, 'md5');
                }
                return ['isUpload'=>true,'msg'=>$savename]; 
            }
            // 单文件处理
            $savename = \think\facade\Filesystem::putFile( $path, $files, 'md5');
            return ['isUpload'=>true, 'msg'=>[$savename]];
        }catch (\RuntimeException $e) {
            return ['isUpload'=>false,'msg'=>$e->getMessage()];
        }
    }
}
