<?php
namespace fastphp\base;
use fastphp\helper\DbHelper;
class Model extends DbHelper
{
	//==============================================
	//====以下是模型文件中，可以直接$this->调用的方法=========
	/**
	 * $this->sql($sql) 		//返回结果集，用于查询
	 * $this->execute($sql) 	//返回影响行数，用于新增、删除、修改
	 
	 * $this->find($field='*',where='')
	 * $this->select($field='*',$where)
	 
	 * $this->insert($data=array('name'=>'li','age'=>28))
	 * $this->delete($where)
	 * $this->update($data,$where)
	 */
	//================================================

    // 当前数据库操作对象
    public $db               	=   null;
    // 数据表前缀
    protected $tablePrefix      =   null;
    // 模型名称
    protected $name             =   '';
    // 数据表名（不包含表前缀）
    protected $tableName        =   '';
    // 实际数据表名（包含表前缀）
	protected $trueTableName    =   '';
	
    public function __construct($name='',$tablePrefix='')
    {
		$this->name = $name;
		if(empty($this->name)){
            $this->name = $this->getModelName();
        }
        // 设置表前缀
        if(is_null($tablePrefix)) {// 前缀为Null表示没有前缀
            $this->tablePrefix = '';
        }elseif('' != $tablePrefix) {
			$this->tablePrefix = $tablePrefix;
        }elseif(!isset($this->tablePrefix)){
			$this->tablePrefix = DB_PREFIX;
		}
		$this->trueTableName = $this->getTableName();
		$this->db();
	}
    public function getModelName() {
        if(empty($this->name)){
            $name = substr(get_class($this),0,-strlen('Model'));
            if ( $pos = strrpos($name,'\\') ) {//有命名空间
                $this->name = substr($name,$pos+1);
            }else{
                $this->name = $name;
            }
        }
        return $this->name;
	}
    /**
     * 得到完整的数据表名
     * @access public
     * @return string
     */
    public function getTableName() {
        if(empty($this->trueTableName)) {
            $tableName  = !empty($this->tablePrefix) ? $this->tablePrefix : '';
            if(!empty($this->tableName)) {
                $tableName .= $this->tableName;
            }else{
                $tableName .= parse_name($this->name);
            }
            $this->trueTableName    =   strtolower($tableName);
        }
        return $this->trueTableName;
    }
    private function db() {
        $config=array(
            'host'       => DB_HOST,
            'user'       => DB_USER,
            'pass'       => DB_PASS,
            'port'       => DB_PORT,
            'db'         => DB_NAME,
            'charset'    => 'utf8',
        );
        $this->db = DbHelper::getIntance($config);
        // return $this->db;
	}
	//执行sql语句的方法，返回资源类型
	private function query($sql){
		$res=mysqli_query($this->db->link,$sql);
		if(!$res){
			echo "sql execute fail".PHP_EOL;
			echo "sql is: # ".$sql.' # '.PHP_EOL;
			echo "error code:".mysqli_errno($this->db->link).PHP_EOL;
			echo "error message:".mysqli_error($this->db->link).PHP_EOL;
		}
		return $res;
	}
	//获得最后一条记录id
	private function getInsertid(){
		return mysqli_insert_id($this->db->link);
	}
	//返回受影响的行数
	public function getAffectRows(){
		return mysqli_affected_rows($this->db->link);
	}
	//获取一条记录,前置条件通过资源获取一条记录
	private function getFormSource($query,$type="assoc"){
		if(!in_array($type,array("assoc","array","row")))
		{
			die("mysqli_query error");
		}
		$funcname="mysqli_fetch_".$type;
		return $funcname($query);
	}
	//获取数据，一维数组
	public function find($field='*',$where=''){
		if(empty($where)){
			$query=$this->query("select $field from $this->trueTableName limit 1");
		}else{
			$query=$this->query("select $field from $this->trueTableName where $where limit 1");
		}
		$list=array();
		while ($r=$this->getFormSource($query)) {
			$list=$r;
		}
		return $list;
	}	
	//获取数据，二维数组
	public function select($field='*',$where=''){
		if(empty($where)){
			$query=$this->query("select $field from $this->trueTableName");
		}else{
			$query=$this->query("select $field from $this->trueTableName where $where");
		}

		$list=array();
		while ($r=$this->getFormSource($query)) {
			$list[]=$r;
		}
		return $list;
	}	
	//执行SQL，返回结果数组
	public function sql($sql){
		$query=$this->query($sql);
		$list=array();
		while ($r=$this->getFormSource($query)) {
			$list[]=$r;
		}
		return $list;
	}
	//执行SQL，返回受影响行数
	public function execute($sql){
		$this->query($sql);
		return $this->getAffectRows();
	}	
	/**
	* 定义添加数据的方法
	* @param string $table 表名
	* @param string orarray $data [数据]
	* @return int 最新添加的id
	*/
	public function insert($data,$returnId=True){
		//遍历数组，得到每一个字段和字段的值
		$key_str='';
		$v_str='';
		foreach($data as $key=>$v){
			//$key的值是每一个字段s一个字段所对应的值
			$key_str.=$key.',';
			$v_str.="'$v',";
		}
		$key_str=trim($key_str,',');
		$v_str=trim($v_str,',');
		//判断数据是否为空
		$sql="insert into $this->trueTableName ($key_str) values ($v_str)";
		$this->query($sql);
		if($returnId){
			//返回上一次增加操做产生ID值
			return $this->getInsertid();
		}else {
			//返回插入行数
			return mysqli_affected_rows($this->db->link);
		}

	}
	/*
	* 删除一条数据方法
	* @param1 $table, $where=array('id'=>'1') 表名 条件
	* @return 受影响的行数
	*/
	public function delete($where){
		if(is_array($where)){
			foreach ($where as $key => $val) {
				$condition[] = $key.'='.$val;
			}
			$condition = implode(' and ',$condition);
		} else {
			$condition = $where;
		}
		$sql = "delete from $this->trueTableName where $condition";
		$this->query($sql);
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
	public function update($data,$where){
		//遍历数组，得到每一个字段和字段的值
		$str='';
		foreach($data as $key=>$v){
			$str.="$key='$v',";
		}
		$str=rtrim($str,',');
		//修改SQL语句
		$sql="update $this->trueTableName set $str where $where";
		$this->query($sql);
		//返回受影响的行数
		return mysqli_affected_rows($this->db->link);
	}
}