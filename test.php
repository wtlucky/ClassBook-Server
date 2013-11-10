<?php
/*
 * Created by wtlucky
 * Created on 2012-12-27
 *
 */

function __autoload($className) {
 	require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件  
}  

//$m = new MysqliDB();
//$a = $m->get('CBUser');
//Response::sharedInstance()->sendResponse(200,json_encode($a));
$AccountID = $_SERVER['PHP_AUTH_USER'];
$AccountPassword = $_SERVER['PHP_AUTH_PW'];
if ( isset($AccountID) && isset($AccountPassword) )
{
    echo("user:".$AccountID);
    echo("pwd:".$AccountPassword);
}
else
{
    header('WWW-Authenticate: Basic realm="Need Authorization"');
}
print_r($_SERVER);
?>
