<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-1-16
 * Time: 下午3:14
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 发送分组留言接口。
 * 需要post过来的数据用户ID、分组ID、留言内容。
 * 发送完成返回留言ID
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST['user_id']) && isset($_POST['group_id']) && isset($_POST['msg_content']))
{
    $db = MysqliDB::getInstance();

    $insertArray = array(
        'user_id' => $_POST['user_id'],
        'group_id' => $_POST['group_id'],
        'msg_content' => $_POST['msg_content'],
        'msg_time' => $_POST['msg_time']
    );

    $result = $db->insert('CBMessage',$insertArray);

    //判断发送是否成功
    if ($result)
    { 
        $idResult = $db->rawQuery("select CBMessage.msg_id from CBMessage 
        where CBMessage.user_id = ? AND group_id =? AND msg_content =? AND msg_time =?",$insertArray);
        Response::sharedInstance()->sendResponse(200, json_encode(array($idResult)));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, array('发送留言失败！'));
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, array('参数传递不完整！'));
}

?>
