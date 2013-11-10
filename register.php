<?php
/**
 * Created with JetBrains PhpStorm.
 * User: gaoxiuyu
 * Date: 13-3-1
 * Time: 下午2:00
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 注册信息获取接口。
 * 需要post过来的数据:昵称（name）、邮箱（email）密码（password）。
 * 返回唯一ID。
 */

//自动加载class 文件
function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php';
}

//itswyy
//创建多级文件夹
function create_folders($dir){
    return is_dir($dir) or (
        create_folders(dirname($dir))
            and mkdir($dir, 0777));
}

//判断数据是否为空
if(isset($_POST['name']) && (isset($_POST['email']) || isset($_POST['tel_num'])) && isset($_POST['password']))
{
    $db = MysqliDB::getInstance();

    //产生一个随机Id
    $userid = mt_rand(100000,999999);
//    echo $userid.'hello ';
    $uid = array($userid);

    //检测重复
    $idResult = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.user_id = ?",$uid);
    while($idResult)
    {
        //产生一个随机Id
        $userid = mt_rand(100000,999999);
//        echo $userid;
        $uid = array($userid);
        $idResult = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.user_id = ?",$uid);
    }

    //判断昵称是否已经被注册
	$params1 = array($_POST['name']);
    $result1 = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.name = ?", $params1);
	if($result1)//如果昵称已经被注册则提示用户更换昵称
	{
		Response::sharedInstance()->sendResponse(500, '该昵称已经被注册，请换一个昵称重新注册！');
        exit;
	}

    if(isset($_POST['email']))
    {
//判断邮箱是否已经被注册
        $params2 = array($_POST['email']);
        $result2 = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.email = ?", $params2);
        if($result2)//如果邮箱已经被注册则提示用户更换邮箱
        {
            Response::sharedInstance()->sendResponse(500, '该邮箱已经被注册，请换一个邮箱重新注册！');
            exit;
        }
    }
    else if(isset($_POST['tel_num']))
    {
        //判断邮箱是否已经被注册
        $params3 = array($_POST['tel_num']);
        $result2 = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.tel_num = ?", $params3);
        if($result2)//如果邮箱已经被注册则提示用户更换邮箱
        {
            Response::sharedInstance()->sendResponse(500, '该手机号已经被注册，请换一个手机号重新注册！');
            exit;
        }
    }

//如果昵称和邮箱均没有被注册过则进行注册
//        echo $userid; //此处能够得到随机id
        $insertArray = array(
        'user_id'=>$userid,
        'email' => $_POST['email'],
        'name' => $_POST['name'],
        'password' => $_POST['password'],
        'tel_num' => $_POST['tel_num']);

        $result3 = $db->insert('cbuser',$insertArray);

        //查询是否插入成功
        $uid = array($userid);
        $idResult = $db->rawQuery("select cbuser.user_id from cbuser where cbuser.user_id = ?",$uid);
   		//判断发送是否成功
        if ($idResult)
        {
            //拼接字符串
            $strid = strval($userid);
            $dirImages = "./Users/".$strid."/images";
            $dirVoices = "./Users/".$strid."/voices";

            //创建对应账号下image和voices 目录
            create_folders($dirImages);
            create_folders($dirVoices);
            $userArr = array($userid);
            //返回userid
			Response::sharedInstance()->sendResponse(200,json_encode($userArr));
        }
        else
        {
        	$response = array('注册失败！');
            Response::sharedInstance()->sendResponse(500,json_encode($response));
        }
}
else
{
	$response = array('参数传递不完整！');
    Response::sharedInstance()->sendResponse(406, $response);
}


?>