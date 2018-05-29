<?php
namespace app\model;
use fastphp\base\Model;

/**
 * 用户Model
 */
class UserModel extends Model
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }
    public function getlist(){
        $result = $this->model->select('user');
        return $result;
    }
    public function getinfo($id){
        // $result = $this->model->start();
        $result = $this->model->find('user','id='.$id);
        return $result;
    }
    public function save($data,$where){
        // $result = $this->model->start();
        $result = $this->model->update('user',$data,$where);
        return $result;
    }
    public function deltask(){
        // $this->model->delete('user',$data,$where);
    }
}