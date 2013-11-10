<?php
/**
 * Created with JetBrains PhpStorm.
 * User: gaoxiuyu
 * Date: 13-3-1
 * Time: 下午2:44
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 登录信息获取接口。
 * 需要post过来的数据:昵称（name）或者邮箱（email）或者手机号（tel_num）或者唯一ID（user_id）和密码（password）。
 * 返回用户的唯一ID。
 */

//自动加载 class 文件
function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; 
}

//判断数据是否为空
if((isset($_POST['name']) || isset($_POST['user_id']) || isset($_POST['email']) || isset($_POST['tel_num'])) && isset($_POST['password']))
{
	$db = new MysqliDb();
	//$updateArray = array('enter_state' => 1);//登录状态
	//昵称登录
	if(isset($_POST['name']))
	{
		//验证登录信息是否合法
        $params1 = array($_POST['name'],$_POST['password']);
        $resutls1 = $db->rawQuery("select cbuser.user_id from cbuser
where cbuser.name = ? and cbuser.password = ? and cbuser.enter_state = 0", $params1);
        if($resutls1)
		{
			$resArr1 = array($resutls1[0][user_id]);
			Response::sharedInstance()->sendResponse(200, json_encode($resArr1));
	        //$results2 = $db->where('name',$_POST['name'])->update('cbuser',$updateArray);
		}
		else
		{
			Response::sharedInstance()->sendResponse(500, '该用户名与密码不匹配或者已经在其他设备上登录！');
		}
	}
	else if(isset($_POST['user_id']))//唯一ID登录
	{
		//验证登录信息是否合法
        $params2 = array($_POST['user_id'],$_POST['password']);
        $resutls2 = $db->rawQuery("select cbuser.user_id from cbuser
where cbuser.user_id = ? and cbuser.password = ? and cbuser.enter_state = 0", $params2);
        if($resutls2)
		{
		    $resArr2 = array((int)$_POST['user_id']);
			Response::sharedInstance()->sendResponse(200, json_encode($resArr2));
	        //$result = $db->where('user_id',$_POST['user_id'])->update('cbuser',$updateArray);
		}
		else
		{
			Response::sharedInstance()->sendResponse(500, '该用户名与密码不匹配或者已经在其他设备上登录！');
		}
	}
	else if(isset($_POST['email']))//邮箱登录
	{
		//验证登录信息是否合法
        $params3 = array($_POST['email'],$_POST['password']);
        $resutls3 = $db->rawQuery("select cbuser.user_id from cbuser
where cbuser.email = ? and cbuser.password = ? and cbuser.enter_state = 0", $params3);
        if($resutls3)
		{
			$resArr3 = array($resutls3[0][user_id]);
			Response::sharedInstance()->sendResponse(200, json_encode($resArr3));
	        //$result = $db->where('email',$_POST['email'])->update('cbuser',$updateArray);
		}
		else
		{
			Response::sharedInstance()->sendResponse(500, '该用户名与密码不匹配或者已经在其他设备上登录！');
		}
	}
	else//手机号登录
	{
		//验证登录信息是否合法
        $params4 = array($_POST['tel_num'],$_POST['password']);
        $resutls4 = $db->rawQuery("select cbuser.user_id from cbuser
where cbuser.tel_num = ? and cbuser.password = ? and cbuser.enter_state = 0", $params4);
        if($resutls4)
		{
			$resArr4 = array($results4[0][user_id]);
			Response::sharedInstance()->sendResponse(200, json_encode($resArr4));
	        //$result = $db->where('tel_num',$_POST['tel_num'])->update('cbuser',$updateArray);
		}
		else
		{
			Response::sharedInstance()->sendResponse(500, '该用户名与密码不匹配或者已经在其他设备上登录！');
		}
	}
}
else
{
	Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}
?>