<?php
/**
 * user
 */
namespace app\controller;
use fastphp\base\Controller;
use app\model\UserModel;
use app\model\QueueModel;
class User extends Controller
{
    public function index(){
        $user = new UserModel();
    }
    public function upstatus()
    {
        //执行具体任务
        $id = $_GET['id'];
        //修改用户状态为1
        $user = new UserModel();
        $info = $user->getinfo($id);
        if(!empty($info) && $info['status']==0){
            $data['status'] = 1;
            $row = $user->save($data,'id='.$id);
            if($row){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    //生成任务
    public function mtask(){
        $times = !empty($_GET['times']) ? intval($_GET['times']) : '1';
        $this->queue = new QueueModel();
        $url = 'http://www.mvc.com/user/upstatus';
        //查询model中的ID最大值
        $maxid = $this->queue->getmaxid();
        $startid = $maxid+1;
        $endid = $startid+$times;
        for ($i=$startid; $i <$endid ; $i++) { 
            $params['id'] = $i;
            $this->queue->set_type('url', 'get', $url);
            $this->queue->queuePush($params, 3);
        }
    }

    //删除任务
    public function dtask(){
        $user = new UserModel();
        $user->delsql();
    }

}