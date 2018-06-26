<?php
/**
 * 配置文件
 *
 * @author lypeng
 * @date 2017-12-28
 **/
return array(
	//database
	'DB_HOST'		=> 'localhost',
	'DB_USER'		=> 'root',
	'DB_PASS'		=> 'root',
	'DB_PORT'		=> '3306',
	'DB_NAME'		=> 'mvc',
	'DB_PREFIX'		=> '',
	//system
	'DEFAULT_CONTROLLER'	=> 'Index',
	'DEFAULT_ACTION'	=> 'index',
	//mail
	'MAIL_NAME'		=> '893371810@qq.com',
	//cache
	// 'DIR_CACHE'		=>	DIR_ROOT . '/data/cache',
	//db cache
	'CACHE_TYPE'	=>	'Db',
	'DATA_CACHE_PREFIX'	=>	'',
	'DATA_CACHE_TABLE'	=>	'sys_cache',
	'DATA_CACHE_TIME'	=>	68400
);
?>