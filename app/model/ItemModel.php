<?php
namespace app\model;

use fastphp\base\Model;
// use fastphp\helper\Db;

/**
 * 用户Model
 */
class ItemModel extends Model
// class ItemModel extends Model
// class ItemModel
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
        // echo '99';
        $this->item = new Model();
        var_dump($this->db);
        // $this->db = $this->model->init();
    }
    // /**
    //  * 搜索功能，因为Sql父类里面没有现成的like搜索，
    //  * 所以需要自己写SQL语句，对数据库的操作应该都放
    //  * 在Model里面，然后提供给Controller直接调用
    //  * @param $title string 查询的关键词
    //  * @return array 返回的数据
    //  */
    public function search($keyword)
    {
        // echo DB_HOST;
        // $sql = "select * from `$this->table` where `item_name` like :keyword";
        // $sth = Db::pdo()->prepare($sql);
        // $sth = $this->formatParam($sth, [':keyword' => "%$keyword%"]);
        // $sth->execute();

        // return $sth->fetchAll();
    }
    public function getlist(){
        // $result = $this->model->start();
        $result = $this->item->select('item','','*');
        return $result;
    }
}