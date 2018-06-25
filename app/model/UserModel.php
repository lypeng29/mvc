<?php
namespace app\model;
use fastphp\base\Model;

/**
 * 用户Model
 */
class UserModel extends Model
{
    public function getlist(){
        $result = $this->select();
        return $result;
    }
    public function getinfo($id){
        $result = $this->find('*','id='.$id);
        return $result;
    }
    public function save($data,$where){
        $result = $this->update($data,$where);
        return $result;
    }
    public function deltask(){
        // $this->delete($where);
    }
}