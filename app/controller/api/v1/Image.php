<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\Image as ImageModel;

class Image extends BaseController
{
    public function uploadMore()
    {
        $list = (new ImageModel())->uploadRes();
        return self::resCode('success', ['list'=>$list]);
    }
}
