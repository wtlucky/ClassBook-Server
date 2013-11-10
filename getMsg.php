<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-2-11
 * Time: 下午1:44
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 获取分组留言接口。
 * 需要post过来的数据:分组ID、最早留言时间（可选）。
 * 返回符合要求的全部留言信息（返回数据中的分组ID变为接收组的ID）
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[group_id]))
{
    $db = new MysqliDb();

    $params = array($_POST[group_id], isset($_POST[msg_time]) ? $_POST[msg_time] : 0, $_POST[group_id]);
    $resutls = $db->rawQuery("select CBMessage.msg_id, ? as group_id, CBMessage.user_id, CBMessage.msg_time, CBMessage.msg_content from CBMessage, CBMsgReceive
where CBMessage.msg_time > ? and CBMsgReceive.msg_id = CBMessage.msg_id and CBMsgReceive.group_id = ?", $params);

    //判断查询是否成功
    if ($resutls)
    {
        Response::sharedInstance()->sendResponse(200, json_encode($resutls));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '获取留言失败或无数据！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
