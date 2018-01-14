<?php
namespace fastphp\base;
use fastphp\helper\Db;
class Model extends Db
{
    /**
     * 模型基类，提供增删改查等操作
     */
    // 当前数据库操作对象
    public $db               =   null;
    public function __construct()
    {
        $this->db();
    }
    public function db() {
        $config=array(
            'host'       => DB_HOST,
            'user'       => DB_USER,
            'pass'       => DB_PASS,
            'port'       => DB_PORT,
            'db'         => DB_NAME,
            'charset'    => 'utf8',
        );
        $this->db = Db::getIntance($config);
        return $this->db;
    }
	//==============================================
	//====以下是可以调用的方法=========
	/**
	 * sql($sql) select($table,$where='',$field='*')
	 * insert($table,$data=array('name'=>'li','age'=>28))
	 * delete($table,$where)
	 * update($table,$data,$where)
	 */
	//================================================
	//获取数据，一维数组
	public function find($table,$where='',$field='*'){
		if(empty($where)){
			$query=$this->db->query("select $field from $table limit 1");
		}else{
			$query=$this->db->query("select $field from $table where $where limit 1");
		}
		$list=array();
		while ($r=$this->db->getFormSource($query)) {
			$list=$r;
		}
		return $list;
	}	
	//获取数据，二维数组
	public function select($table,$where='',$field='*'){
		if(empty($where)){
			$query=$this->db->query("select $field from $table");
		}else{
			$query=$this->db->query("select $field from $table where $where");
		}
		$list=array();
		while ($r=$this->db->getFormSource($query)) {
			$list[]=$r;
		}
		return $list;
	}	
	//获取多条数据，二维数组
	public function sql($sql){
		$query=$this->db->query($sql);
		$list=array();
		while ($r=$this->db->getFormSource($query)) {
			$list[]=$r;
		}
		return $list;
	}
	/**
	* 定义添加数据的方法
	* @param string $table 表名
	* @param string orarray $data [数据]
	* @return int 最新添加的id
	*/
	public function insert($table,$data){
		//遍历数组，得到每一个字段和字段的值
		$key_str='';
		$v_str='';
		foreach($data as $key=>$v){
			if(empty($v)){
				die("error");
			}
			//$key的值是每一个字段s一个字段所对应的值
			$key_str.=$key.',';
			$v_str.="'$v',";
		}
		$key_str=trim($key_str,',');
		$v_str=trim($v_str,',');
		//判断数据是否为空
		$sql="insert into $table ($key_str) values ($v_str)";
		$this->db->query($sql);
		//返回上一次增加操做产生ID值
		return $this->db->getInsertid();
	}
	/*
	* 删除一条数据方法
	* @param1 $table, $where=array('id'=>'1') 表名 条件
	* @return 受影响的行数
	*/
	public function delete($table,$where){
		if(is_array($where)){
			foreach ($where as $key => $val) {
				$condition = $key.'='.$val;
			}
		} else {
			$condition = $where;
		}
		$sql = "delete from $table where $condition";
		$this->db->query($sql);
		//返回受影响的行数
		return mysqli_affected_rows($this->db->link);
	}
	/**
	* [修改操作description]
	* @param [type] $table [表名]
	* @param [type] $data [数据]
	* @param [type] $where [条件]
	* @return [type]
	*/
	public function update($table,$data,$where){
		//遍历数组，得到每一个字段和字段的值
		$str='';
		foreach($data as $key=>$v){
			$str.="$key='$v',";
		}
		$str=rtrim($str,',');
		//修改SQL语句
		$sql="update $table set $str where $where";
		$this->db->query($sql);
		//返回受影响的行数
		return mysqli_affected_rows($this->db->link);
	}
}