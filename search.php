<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User -- wangxuanao
 * Date: 13-4-16
 * Time: 上午9:44
 * To change this template use File | Settings | File Templates.
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}
//判断数据是否为空
//用户id查询
if (isset($_POST[user_id])&& strcmp($_POST[user_id],"") )
{
    $db = new MysqliDb();

    $params = array($_POST[user_id]);
    $resutls = $db->rawQuery("select user_id,name,head_portrait,email,tel_num,bloodtype,sex,birthday,constellation,race,qq,address,sina_weibo,longtitude,latitude,password,real_name from cbuser where user_id = ?", $params);

    //判断查询是否成功
    if ($resutls)
    {
        Response::sharedInstance()->sendResponse(200, json_encode($resutls));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '无数据！');
    }
}
else if(isset($_POST[name])&& strcmp($_POST[name],""))
{
    $db = new MysqliDb();

    $params = array($_POST[name]);

    //以后再添加上模糊查询吧！！！！！！！！！！！！！！！！！！！！！！！！！！！！
    $resutls = $db->rawQuery("select user_id,name,head_portrait,email,tel_num,bloodtype,sex,birthday,constellation,race,qq,address,sina_weibo,longtitude,latitude,password,real_name from cbuser where cbuser.name like ?",$params);

    //判断查询是否成功
    if ($resutls)
    {
        Response::sharedInstance()->sendResponse(200, json_encode($resutls));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '无数据！');
    }
}
else if(isset($_POST[tel_num])&& strcmp($_POST[tel_num],""))
{
    $db = new MysqliDb();

    $params = array($_POST[tel_num]);

    $resutls = $db->rawQuery("select user_id,name,head_portrait,email,tel_num,bloodtype,sex,birthday,constellation,race,qq,address,sina_weibo,longtitude,latitude,password,real_name from cbuser where tel_num like ?",$params);

    //判断查询是否成功
    if ($resutls)
    {
        Response::sharedInstance()->sendResponse(200, json_encode($resutls));
    }
    else
    {
        Response::sharedInstance()->sendResponse(500, '无数据！');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}


?>