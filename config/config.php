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

    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
    // 'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'DATA_CACHE_TYPE'       =>  'Memcache',  // 数据缓存类型,支持:File|Db|Memcache|Memcached|Redis
    'DATA_CACHE_PATH'       =>  DIR_ROOT . '/data/cache',// 缓存路径设置 (仅对File方式缓存有效)
	'DATA_CACHE_TABLE'		=>	'sys_cache', // 缓存数据库表（针对Db缓存）
	'DATA_CACHE_TIMEOUT'	=>	false,

	// 'REDIS_HOST'	=>	'127.0.0.1',
	// 'REDIS_PORT'	=>	6379,

	//memcache
	'MEMCACHE_HOST'	=>	'127.0.0.1',
	'MEMCACHE_PORT'	=>	11211,

);
?>