<?php
/**
 * Created with JetBrains PhpStorm.
 * User: wtlucky
 * Date: 13-1-16
 * Time: 下午3:14
 * Copyright (c) 2012年 AlphaStudio. All rights reserved.
 */

/*
 * 发送邮件接口。
 * 需要post过来的数据邮件标题（email_subject）、邮件内容（email_subject）。
 * 返回发送结果
 */

function __autoload($className) {
    require_once 'config.inc.php';
    require_once ROOT_PATH . '/includes/'. ucfirst($className) .'.class.php'; //自动加载 class 文件
}

//判断数据是否为空
if (isset($_POST[email_subject]) && isset($_POST[email_body]))
{
    $to = "classbook@hotmail.com";
    $subject = $_POST[email_subject];
    $message = $_POST[email_body];
    //$header = "From:cbclassbook@163.com \r\n";
    //$retval = mail ($to,$subject,$message);//,$header);
    
    $smtpserver = "smtp.163.com";//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = "cbclassbook@163.com";//SMTP服务器的用户邮箱
    $smtpemailto = $to;//发送给谁
    $smtpuser = "cbclassbook@163.com";//SMTP服务器的用户帐号
    $smtppass = "edu2act.org";//SMTP服务器的用户密码
    $mailsubject = $subject;//邮件主题
    $mailbody = $message;//邮件内容
    $mailtype = "TXT";//邮件格式（HTML/TXT）,TXT为文本邮件

    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    //$smtp->debug = TRUE;//是否显示发送的调试信息
    $send=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);    
    
    if( $send == true )  
    {
       Response::sharedInstance()->sendResponse(200, json_encode(array('反馈成功！')));
    }
    else
    {
       Response::sharedInstance()->sendResponse(500, '邮件发送失败');
    }
}
else
{
    Response::sharedInstance()->sendResponse(406, '参数传递不完整！');
}

?>
