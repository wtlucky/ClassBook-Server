<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-1-13
 * Time: 下午2:37
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 创建用户分组接口。
 * 需要post过来的数据用户ID、分组名、分组头像。
 * 创建完成返回分组ID
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) . '.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[user_id]) && isset($_POST[group_name]) && isset($_POST[group_image]))
{
    $db = new MysqliDB();

    $insertArray = array(
        'user_id' => $_POST[user_id],
        'group_name' => $_POST[group_name],
        'group_iamge' => $_POST[group_image]
    );

    $result = $db->insert('CBGroup',$insertArray);

    //判断创建是否成功
    if ($result)
    {
        Response::sharedInstance()->sendResponse(200, json_encode($db->getInsertId()));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '分组创建失败！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
