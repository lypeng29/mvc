<?php
/**
 * 入口文件
 *
 * @author lypeng
 * @date 2018-1-11
 */
// 应用目录为当前目录
define('DIR_ROOT', __DIR__ . '/');

// 开启调试模式
define('APP_DEBUG', true);

//加载函数文件
require(DIR_ROOT.'fastphp/function.php');
// 加载配置文件
$config = require(DIR_ROOT . 'config/config.php');
// 加载框架文件
require(DIR_ROOT . 'fastphp/Fastphp.php');
//加载vendor文件
require(DIR_ROOT.'vendor/autoload.php');

// 实例化框架类
(new fastphp\Fastphp($config))->run();