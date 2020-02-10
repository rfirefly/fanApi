<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Image extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function uploadImg($field = '', $userId = '')
    {
        // 获取上传文件
        $files = \request()->file($field);
        $user_id = \request()->userId;
        // 文件上传到服务器
        $res = \app\controller\FileController::upload($field, $files, \config('api.uploadsPath'));
        if(!$res['isUpload']) \ApiException($res['msg'], 20003, 200);
        // url写入数据库
        $imgList = [];
        for ($i=0; $i < \count($res['msg']); $i++) { 
            $imgList[] = [
                'url' => str_replace('\\', '/', $res['msg'][$i]),
                'user_id'=> $user_id
            ];
        }
        // \halt(\getFileUrl($imgList[0]['url']));
        // return \getFileUrl($imgList[0]['url']);
        return self::saveAll($imgList);
    }

    public function uploadRes()
    {
        $imgList = $this->uploadImg('imgList', \request()->userId);
        for ($i=0; $i < \count($imgList); $i++) {
            $url = \getFileUrl($imgList[$i]['url'], true);
            $imgList[$i]['url'] = $url;
        }
        return $imgList;
    }

    // 图片是否存在
    public function isImageExist($id,$userid){
        return $this->where('user_id',$userid)->field('id')->find($id);
    }

    public function getUrlAttr($value, $data)
    {
        return \getFileUrl($data['url'], true);
    }
}
