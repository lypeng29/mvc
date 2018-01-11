<?php
namespace fastphp\helper;
class DbHelper
{
    private $link;
    private $rows;
    static private $_instance;

    // 连接数据库
    private function __construct($host, $username, $password)
    {
        $this->link = mysql_connect($host, $username, $password);
        $this->query("SET NAMES 'utf8'", $this->link);
        return $this->link;
    }
    private function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }
    //返回这个类的说明信息 echo $_instance
    function __tostring(){
         return "mysql类，建立于2012-10-30 23:19，有参照其他人的类！";
    }
    //版本信息
	function version() {
		return "当前mysql版本为：".mysql_get_server_info();
	}    
    public static function getInstance(){
        if(FALSE == (self::$_instance instanceof self)){
            self::$_instance = new self($host, $username, $password);
        }
        return self::$_instance;
    }
    /**
     * 查询
     */
    public function query($sql, $link = '') {
        $this->result = mysql_query($sql, $this->link) or $this->err($sql);
        return $this->result;
    }
    /**
     * SELECT
     */
	public function select($table, $field = '',$condition = '') {
		$wh=$condition=='' ? '' : 'where '.$condition;
		if($field==""){
		return $this->query("SELECT * FROM $table $wh");
		}else{
		return $this->query("SELECT $field FROM $table $wh");
		}
    }
    /**
     * DELETE
     */
	public function delete($table, $condition) {
		return $this->query("DELETE FROM $table WHERE $condition");
	}
    /**
     * INSERT
     */
	public function insert($table, $field, $value) {
		return $this->query("INSERT INTO $table ($field) VALUES ($value)");
	}
    /**
     * UPDATE
     */
	public function update($table, $update_content, $condition) {
		return $this->query("UPDATE $table SET $update_content WHERE $condition");
	}    
    /**
     * 单行记录
     */
    public function getRow($sql, $type = MYSQL_ASSOC) {
        $result = $this->query($sql);
        return @ mysql_fetch_array($result, $type);
    }
    /**
     * 多行记录
     */
    public function getRows($sql, $type = MYSQL_ASSOC) {
        $result = $this->query($sql);
        while ($row = @ mysql_fetch_array($result, $type)) {
            $this->rows[] = $row;
        }
        return $this->rows;
    }
	public function getCount($sql) {
		$this->result=$this->query($sql);
		if ($this->result == null) {
			$this->err($sql);
		}else{
			return mysql_num_rows($this->result);
		}
	}    
    /**
     * 错误信息输出
     */
    protected function err($sql = '') {
        //这里输出错误信息
        echo 'SQL error: '.$sql;
        exit();
    }
    // 关闭数据库连接
    public function close()
    {
        return $this->result = mysql_close($this->link);
    }
}
//用例
$db = mysql::getInstance();
$db2 = mysql::getInstance();
// $data = $db->getRows('select * from blog');
//print_r($data);
//判断两个对象是否相等
if($db === $db2){
    echo 'true';
}

//select使用方法
//$aa=1;
//$r=$mysql->select("n_admin","username,password","mid=$aa");
//$a=$mysql->fetch_array($r);
//print_r ($a);
//echo md5("ABC");
//返回这个类的说明信息 echo $mysql;
//返回已定义的所用函数
//$fun=get_defined_functions();
//print_r($fun);
//返回已开启的扩展
//print_r(get_loaded_extensions()); 
//删除使用方法
//$mysql->delete('n_admin',"id between 7 and 9");
//插入使用方法
//$mysql->insert('n_admin',"id,mid,username,password,remark","'','2','admin','123123','indse'");
//echo $mysql->insert_id();
//修改使用方法
//$mysql->update('n_admin',"username='name',password='123456'","id='2'");