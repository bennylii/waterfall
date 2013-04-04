<?php

//error_reporting(E_ALL);

// 备案临时用的
//if(!preg_match("/(bot|spider|yahoo)/i", strtolower($_SERVER['HTTP_USER_AGENT']))
//    && $_SERVER['REQUEST_URI'] == '/' && empty($_COOKIE['swb'])){
//    header("Location:/index_ba.html");
//    exit;
//}

//	定义网站根目录地址
define(	'__ROOT__', dirname(dirname(dirname(__FILE__))).'/');

//	加载全局配置文件和公共函数
require(__ROOT__.'include/config.php');
require(__ROOT__.'include/functions.php');


//  定义 ThinkPHP 框架路径 ( 相对于入口文件 )
define( 'THINK_PATH' ,  __ROOT__.'include/ThinkPHP');

// 定义项目名称和路径
define( 'APP_NAME' ,  'www' );
define( 'APP_PATH' ,  __ROOT__.'www/' );

// 2012 10 18 日正式上线
define(	'VERSION_JS'  ,  20121123);
define(	'VERSION_CSS' ,  20121217);

require_once  APP_PATH.'Conf/define.php';
require_once  APP_PATH.'Common/functions.php';

//  加载框架入口文件   
require(THINK_PATH."/ThinkPHP.php");

// 实例化一个网站应用实例 
App::run();
?>