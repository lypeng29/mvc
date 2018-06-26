<?php
/**
 * @author lypeng
 * dbhelper类 mysqli方式，只提供链接数据库，具体实现由model.php基类提供
 */
namespace fastphp\helper;
class DbHelper{
	/**
	 * 数据库类，提供链接数据库，执行sql语句，返回实例
	 */
	private static $dbcon=false;
	private $host;
	private $port;
	private $user;
	private $pass;
	private $database;
	private $charset;
	public $link;
	private function __construct($config=array()){
		$this->host = $config['hostname'] ? $config['hostname'] : 'localhost';
		$this->port = $config['hostport'] ? $config['hostport'] : '3306';
		$this->user = $config['username'] ? $config['username'] : 'root';
		$this->pass = $config['password'] ? $config['password'] : 'root';
		$this->database = $config['database'] ? $config['database'] : 'small2';
		$this->charset=isset($arr['charset']) ? $arr['charset'] : 'utf8';
		//连接数据库
		$this->db_connect();
		//选择数据库
		$this->db_usedb();
		//设置字符集
		$this->db_charset();
	}
	//连接数据库
	private function db_connect(){
		$this->link=mysqli_connect($this->host.':'.$this->port,$this->user,$this->pass);
		if(!$this->link){
			echo "database connect error.<br>";
			echo "error code:".mysqli_errno($this->link)."<br>";
			echo "error message:".mysqli_error($this->link)."<br>";
			exit;
		}
	}
	//设置字符集
	private function db_charset(){
		mysqli_query($this->link,"set names {$this->charset}");
	}
	//选择数据库
	private function db_usedb(){
		mysqli_query($this->link,"use {$this->database}");
	}
	//私有的克隆
	private function __clone(){
		die('clone is not allowed');
	}
	//公用的静态方法
	public static function getInstance($config=''){
		$config = self::parseConfig($config);
		if(self::$dbcon==false){
			self::$dbcon=new self($config);
		}
		return self::$dbcon;
	}
    public static function parseConfig($config){
        if(!empty($config)){
            // if(is_string($config)) {
            //     return self::parseDsn($config);
            // }
            $config =   array_change_key_case($config);
            $config = array (
                'username'      =>  $config['db_user'],
                'password'      =>  $config['db_pass'],
                'hostname'      =>  $config['db_host'],
                'hostport'      =>  $config['db_port'],
                'database'      =>  $config['db_name'],
                'charset'       =>  isset($config['db_charset'])?$config['db_charset']:'utf8'
            );
        }else {
            $config = array (
                'username'      =>  C('DB_USER'),
                'password'      =>  C('DB_PWD'),
                'hostname'      =>  C('DB_HOST'),
                'hostport'      =>  C('DB_PORT'),
                'database'      =>  C('DB_NAME'),
                'charset'       =>  C('DB_CHARSET')
            );
        }
        return $config;
	}

	// 下面这两个是冗余，用于单独调用
	// model基类里面有自己的query与execute

	//执行SQL，返回结果数组
	public function query($sql){
		$query=mysqli_query($this->link,$sql);
		$list=array();
		while ($r=mysqli_fetch_assoc($query)) {
			$list[]=$r;
		}
		return $list;
	}
	//执行SQL，返回受影响行数
	public function execute($sql){
		mysqli_query($this->link,$sql);
		return mysqli_affected_rows($this->link);
	}
}
?>