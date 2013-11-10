<?php
/**
 * Created with JetBrains PhpStorm.
 * User: parker
 * Date: 19/4/13
 * Time: 下午4:20
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

//自动加载 class 文件
function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php';
}

$user_id = $_POST['user_id'];
$user_id2 = $_POST['user_id2'];

if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/pjpeg"))
    && ($_FILES["file"]["size"] < 20000))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

        if (file_exists("upload/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "Users/".$user_id2."/images/" . $_FILES["file"]["name"]);
        }
    }
}
else
{
    echo "Invalid file";
}

//判断数据是否为空
if(isset($user_id) && isset($user_id2))
{
    $db = MysqliDB::getInstance();
    $insertArray = array(
        'user_id'=>$user_id,
        'user_id2' =>$user_id2,
        'advice_add' => $_POST['advice_add'],
        'advice_vedio_add' => $_POST['advice_vedio_add']);
    $result = $db->insert('cbuseradvice',$insertArray);
    if($result)
    {
        echo "insert succeed";
    }
}

?>
