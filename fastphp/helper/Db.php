<?php
namespace fastphp\helper;
class Db{
	/**
	 * 数据库类，提供链接数据库，执行sql语句，返回实例
	 */
	private static $dbcon=false;
	private $host;
	private $port;
	private $user;
	private $pass;
	private $db;
	private $charset;
	public $link;
	private function __construct($config=array()){
		$this->host = $config['host'] ? $config['host'] : 'localhost';
		$this->port = $config['port'] ? $config['port'] : '3306';
		$this->user = $config['user'] ? $config['user'] : 'root';
		$this->pass = $config['pass'] ? $config['pass'] : 'root';
		$this->db = $config['db'] ? $config['db'] : 'small2';
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
		mysqli_query($this->link,"use {$this->db}");
	}
	//私有的克隆
	private function __clone(){
		die('clone is not allowed');
	}
	//公用的静态方法
	public static function getIntance($config){
		if(self::$dbcon==false){
			self::$dbcon=new self($config);
		}
		return self::$dbcon;
	}
	//执行sql语句的方法
	public function query($sql){
		$res=mysqli_query($this->link,$sql);
		if(!$res){
			echo "sql execute fail<br>";
			echo "error code:".mysqli_errno($this->link)."<br>";
			echo "error message:".mysqli_error($this->link)."<br>";
		}
		return $res;
	}
	//获得最后一条记录id
	public function getInsertid(){
		return mysqli_insert_id($this->link);
	}

	//获取一条记录,前置条件通过资源获取一条记录
	public function getFormSource($query,$type="assoc"){
		if(!in_array($type,array("assoc","array","row")))
		{
			die("mysqli_query error");
		}
		$funcname="mysqli_fetch_".$type;
		return $funcname($query);
	}
}
?>