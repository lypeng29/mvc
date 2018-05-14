<?php
namespace app\model;

use fastphp\base\Model;
use fastphp\helper\TimeHelper;
/**
 * ifeng文章列表model
 */
class IfenglistModel extends Model
{
    private $model;
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }
    public function addall($arr){        
        $sql = "insert into ifenglist(name,url) values";
        foreach ($arr as $key => $value) {
            $sql.="('".$value['name']."','".$value['url']."'),";
        }
        $sql = rtrim($sql,',');
        //返回受影响行数
        $count = $this->model->execute($sql);
        return $count;   
    }    
    public function getlist($type){
        $result = $this->model->select('ifenglist');
        return $result;
    }
}