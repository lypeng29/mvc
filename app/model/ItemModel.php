<?php
namespace app\model;

use fastphp\base\Model;

/**
 * 用户Model
 */
class ItemModel extends Model
{

    //可以调用$this->select,find,sql,execute,insert,update,delete方法
    function __construct(){
        $this->tableName = 'itemb'; // 不包含前缀表名
        // $this->trueTableName = 'dp_itemb'; // 真实表名，如果不指定默认为模型名称
        parent::__construct();//继承父类初始化需要放在最后，否则上面的定义是无效的
    }

    public function search($keyword){
        return $this->sql("select * from itemb where `item_name` like '%$keyword%'");
    }
    public function getlist(){
        return $this->select('*','id<10');
    }
    public function add($data){
        return $this->insert($data,false);//第二个参数默认true，返回insertid,false返回插入行数
    }
}