<?php
namespace app\model;

use fastphp\base\Model;
use fastphp\helper\TimeHelper;
/**
 * 财务Model
 */
class FinanceModel extends Model
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
    public function getcategory($tid)
    {
        if($tid != 2){
            $tid = 1;
        }
        $result = $this->model->select('category','tid='.$tid,'cname as label,id as value');
        return $result;
    }
    public function add($arr){
        $data['cid'] = intval($arr['cid']);
        $data['type'] = intval($arr['type']);
        $data['money'] = round(floatval($arr['money']),2);
        $data['addtime'] = intval($arr['addtime']);
        $data['mark'] = strval($arr['mark']);        
        $result = $this->model->insert('finance',$data);
        return $result;   
    }
    public function getlist($type){
        $condition='';
        if(!empty($type)){
            $condition = 'and f.type='.$type;
        }
        $sql = "select f.type,f.money,f.addtime,f.mark,c.cname from finance as f,category as c where c.id=f.cid ".$condition." order by f.id desc";
        //多表联查
        $result = $this->model->sql($sql);
        foreach ($result as $key => $value) {
            $result[$key]['ftime'] = mdate($value['addtime']);
            $result[$key]['atime'] = date('Y-m-d H:i:s',$value['addtime']);
        }
        return $result;
    }
    public function getsum($type,$thismonth=false){
        // if($type != 2){
        //     $type=1;
        // }
        $where = "type=".$type;
        if($thismonth){
            $arr = TimeHelper::month();
            $where .= ' and addtime > '.$arr['0'].' and addtime < '.$arr['1'];
        }
        $sql = "select sum(money) as s from finance where $where";
        $result = $this->model->sql($sql);
        return $result['0']['s'];
    }
}