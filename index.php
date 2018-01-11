<?php
/**
 * 入口文件
 *
 * @author lypeng
 * @date 2018-1-11
 */
namespace app;
//判断php版本
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//设置网站字符集
header("Content-Type:text/html; charset=utf-8");
// 设置系统时区
date_default_timezone_set('PRC');
//根目录，物理路径
define('ROOT_PATH',str_replace('\\','/',dirname(__FILE__)) . '/'); 
//加载函数文件
require('function.php');
//加载配置文件
$config = require('config.php');

//获取控制器与方法名
$controller_name = !empty($_GET['c']) ? trim($_GET['c']) : $config['DEFAULT_CONTROLLER'];
$method = !empty($_GET['a']) ? trim($_GET['a']) : $config['DEFAULT_ACTION'];

//加载控制器文件
$c_path = 'controller/'.$controller_name.'.php';
if (file_exists($c_path)) {
	require($c_path);
}else{
	die('Controller file not exist!');
}

//加载类文件
// require('db.php');

//执行控制器方法
$controller = "app\controller\\".$controller_name;
$instance = new $controller;
$instance->$method();

?>