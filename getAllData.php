<?php
/**
 * Created with JetBrains PhpStorm.
 * User: TheLittleBoy
 * Date: 13-3-11
 * Time: 下午4:00
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 获取用户的所有服务器端数据
 * 需要post过来的数据:用户ID，设备ID
 * 返回查询用户的所有数据
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[user_id])&&$_POST[device_id])
{
    $db = new MysqliDb();

    $params = array($_POST[user_id]);
    //cbuser表查用户信息
    $result1 = $db->rawQuery("select * from cbuser where cbuser.user_id =?", $params);
    unset($result1[0]["check_state"]); 
    unset($result1[0]["enter_state"]); 
    
    //cbgroup表查用户所有分组
    $result2 = $db->rawQuery("select * from cbgroup where cbgroup.user_id =?", $params);
    
    //cbgroupuser表查找用户分组中的所有用户
    $groupuser = $db->rawQuery("SELECT a.user_id, a.group_id FROM cbgroupuser a, cbgroup b WHERE b.user_id = ? AND a.group_id = b.group_id", $params);
    
    //查找该用户的好友信息
    $friends = $db->rawQuery("SELECT * FROM cbuser WHERE user_id IN (SELECT DISTINCT a.user_id FROM cbgroupuser a, cbgroup b WHERE b.user_id = ? AND a.group_id = b.group_id)", $params);
     
    //删掉无用信息
    foreach ($friends as & $value)
    {
        unset($value["check_state"]);
        unset($value["enter_state"]);
    }

    //cbuseradvice表查用户所有赠言
    $result3 = $db->rawQuery("select * from cbuseradvice where cbuseradvice.user_id2 =?", $params);

    //cbotlinemsg表查用户所有离线赠言
    $result4 = $db->rawQuery("select * from cboutlinemsg where user_id = ?",$params);
	
	//删掉离线用户id
	//unset($result4[0]['outline_user_id']);
	foreach ($result4 as & $value) //传递引用进去，不然没效果
	{
	    unset($value['outline_user_id']);
	}
	
	//cbmessage表查用户所有message
	$result7 = $db->rawQuery("select * from cbmessage where user_id = ?",$params);
	
    $nowtime = date('Y-m-d H:i:s',time());
    //更新同步表cbusersync
    $param = array('device_id'=>$_POST['device_id'],'user_id'=>$_POST['user_id'],'sync_time'=>$nowtime);
    //print_r($param);
    $result5 = $db->insert('cbusersync',$param);
    //判断是否插入（更新时间）成功
    $param2 = array($_POST['device_id'],$_POST['user_id'],$nowtime);
    $result6 = $db->rawQuery("select * from cbusersync where device_id = ? and user_id = ? and sync_time = ?",$param2);

    //判断查询是否成功
    if ($result1&&$result2&&$result6)
    {
        $result = array('cbuser'=>$result1,'cbgroup'=>$result2, 'cbgroupuser'=>$groupuser, 'userfriends'=>$friends, 'cbuseradvice'=>$result3,'cboutlinemsg'=>$result4,'cbmessage'=>$result7);
        Response::sharedInstance()->sendResponse(200, json_encode($result));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '获取信息失败 或无数据 或同步表失败！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
