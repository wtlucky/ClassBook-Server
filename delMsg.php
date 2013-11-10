<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-2-11
 * Time: 下午1:31
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 删除分组留言接口。
 * 需要post过来的数据：留言ID。
 * 删除完成返回操作结果
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[msg_id]))
{
    $db = MysqliDB::getInstance();

    $result = $db->where('msg_id',$_POST[msg_id])->delete('CBMessage');

    //判断删除是否成功
    if ($result)
    {
        Response::sharedInstance()->sendResponse(200, '删除留言成功！');
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '删除留言失败！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
