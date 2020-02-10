<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class PostValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'id'=>'require|integer|>:0',
        'text'=>'require',
        'imgList'=>'require|array',
        'isopen'=>'require|in:0,1',
        'topic_id'=>'require|integer|>:0|isTopicExist',
        'post_class_id'=>'require|integer|>:0|isPostClassExist',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene = [
        'create'=>['text','imgList','token','isopen','topic_id','post_class_id'],
        'detail'=>['id']
    ];
}
