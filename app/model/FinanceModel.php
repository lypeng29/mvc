<?php
namespace app\model;

use fastphp\base\Model;
use fastphp\helper\TimeHelper;
/**
 * 财务Model
 */
class FinanceModel extends Model
{
    public function getcategory($tid)
    {
        if($tid != 2){
            $tid = 1;
        }
        $result = $this->sql('select cname as label,id as value from category where tid='.$tid);
        return $result;
    }
    public function add($arr){
        $data['cid'] = intval($arr['cid']);
        $data['type'] = intval($arr['type']);
        $data['money'] = round(floatval($arr['money']),2);
        $data['addtime'] = intval($arr['addtime']);
        $data['mark'] = strval($arr['mark']);        
        $result = $this->insert($data);
        return $result;   
    }
    public function getlist($type){
        $condition='';
        if(!empty($type)){
            $condition = 'and f.type='.$type;
        }
        $sql = "select f.type,f.money,f.addtime,f.mark,c.cname from finance as f,category as c where c.id=f.cid ".$condition." order by f.id desc";
        //多表联查
        $result = $this->sql($sql);
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
        $result = $this->sql($sql);
        return $result['0']['s'];
    }
}