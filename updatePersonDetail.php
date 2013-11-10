<?php
/**
 * Created with JetBrains PhpStorm.
 * User: gaoxiuyu
 * Date: 13-3-3
 * Time: 下午1:30
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 个人信息获取接口。
 * 需要post过来的数据:邮箱（email）、手机号（tel_num）、密码（password）、性别（sex）、血型（bloodtype）、生日（birthday）、星座（constellation）、民族（race）、QQ（QQ）、地址（address）、真实姓名（real_name）、新浪微博（sina_weibo）。
 * 返回操作结果。
 */

//自动加载 class 文件
function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; 
}

//判断传递的数据是否为空
if(isset($_POST['user_id']))
{
	$updateArray=array('email' => $_POST['email'],'tel_num' => $_POST['tel_num'],'password' => $_POST['password'],'sex' => $_POST['sex'],'bloodtype' => $_POST['bloodtype'],'birthday' => $_POST['birthday'],'constellation' => $_POST['constellation'],'race' => $_POST['race'],'QQ' => $_POST['QQ'],'address' => $_POST['address'],'real_name' => $_POST['real_name'],'sina_weibo' => $_POST['sina_weibo']);
	$db = MysqliDB::getInstance();
	$result = $db->where('user_id',$_POST['user_id'])->update('cbuser',$updateArray);
	if($result)
	{
		Response::sharedInstance()->sendResponse(200);
	}
	else
	{
		Response::sharedInstance()->sendResponse(500, '更新个人信息失败');
	}
}
else
{
	Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}
?>