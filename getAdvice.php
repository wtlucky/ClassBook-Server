<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 13-4-20
 * Time: 下午8:25
 * To change this template use File | Settings | File Templates.
 */


/*
 * 获取用户的赠言内容
 * 需要post过来的数据:用户ID，设备ID，赠言时间
 * 返回查询用户的赠言数据
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[user_id])&&$_POST[device_id]&&$_POST[time])
{
    $db = new MysqliDb();

    $param = array($_POST[user_id],$_POST[device_id]);

    $result = $db->rawQuery("select sync_time from cbusersync where user_id = ? and device_id = ?",$param);

    if($result)
    {
        $params = array($_POST[user_id],$result[0]);

        $result2 = $db->rawQuery("select * from cbuseradvice where user_id2 = ? and advice_time >= ?",$param2);

        if($result2)
        {
            Response::sharedInstance()->sendResponse(200, json_encode($result2));
        }
        else
        {
            Response::sharedInstance()->sendResponse(500, '获取信息失败 或 无数据！');
        }
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
