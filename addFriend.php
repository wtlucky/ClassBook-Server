<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-1-16
 * Time: 下午3:14
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 添加好友接口。
 * 需要post过来的数据用户ID、要添加用户ID、要添加的分组id（现阶段留空,默认加到好友组）。
 * 添加完成，返回成功信息
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[user_id]) && isset($_POST[user_id2]))// && isset($_POST[group_id]))
{
    $db = MysqliDB::getInstance();
    
    $param = array($_POST[user_id], "好友");
    
    $result = $db->rawQuery("SELECT group_id FROM CBGroup WHERE user_id = ? AND group_name = ?", $param);
    
    $group_id = $result[0]['group_id'];
    
    //查询好友是否存在
    $searchRs = $db->rawQuery("SELECT * FROM CBGRoupUser WHERE user_id = ? AND group_id = ?", array($_POST[user_id2], $group_id));
    
    if ($searchRs)
    {
        Response::sharedInstance()->sendResponse(500, '添加失败，好友已存在');
        return;
    }
    
    
    //添加好友
    $insertData = array(
	'user_id' => $_POST[user_id2],
	'group_id' => $group_id
    );
    
    $insertResult = $db->insert('CBGroupUser', $insertData);

    //判断添加是否成功
    if ($insertResult)
    {
        Response::sharedInstance()->sendResponse(200, json_encode(array('添加好友成功！')));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '添加好友失败！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
