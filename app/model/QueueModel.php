<?php
namespace app\model;

use fastphp\base\Model;
/**
 * 队列Model
 */
class QueueModel extends Model
{
    private $main_type = '';
    private $sub_type = '';
    public function getmaxid(){
        $info = $this->find('*','id>0 order by id desc');
        return $info['id'];
    }
    /*
     * 设置队列类型
     * @param string $main_type
     * @param string $sub_type
     */
    public function set_type($main_type = '', $sub_type = '', $request_url = '') {
        $this->main_type = $main_type;
        $this->sub_type = $sub_type;
        $this->request_url = $request_url;
    }

    /*
     * 设置锁
     * @param string $data
     */
    public function setlock($data) {
        $update['is_lock'] = 1;
        return $this->update($update,'id='.$data['id']);
    }

    /*
     * 入队
     * @param array $params
     * @param integer $max_times
     * @return integer
     */
    public function queuePush($params = array(), $max_times = 1) {
        if (empty($params) || empty($this->main_type) || empty($this->sub_type)) {
            return false;
        }

        $data['main_type'] = $this->main_type;//类型
        $data['sub_type'] = $this->sub_type;//子类型
        $data['request_url'] = $this->request_url;//请求地址
        $data['params'] = serialize($params);//参数
        $data['max_times'] = $max_times;//重试最大次数
        //$data['exe_times'] = 0;//被执行次数
        //$data['status'] = 0;//执行状态
        $data['add_time'] = time();
        return $this->insert($data);
    }

    /*
     * 出队
     */
    public function queuePull($main_type = 'url', $limit = 100){
        $condition = 'main_type="'. $main_type .'" and is_lock=0 and status in(0,1) and exe_times < max_times';
        $queue_list = $this->select('*',$condition.' order by add_time asc limit '.$limit);
        return $queue_list;
    }

    //删除已执行成功队列
    public function clear(){
        $condition['is_lock'] = 0;
        $condition['status'] = 2;
        return $this->delete($condition);
    }

    //更新
    public function queueUpdate($res, $data){
        // var_dump($res);die;
        if(empty($data)){
            return false;
        }
        $update = 'exe_times=exe_times+1,';//被执行次数
        $exe_num = $data['exe_times']+1;
        //执行状态修改
        if($res){
            $update .= 'is_lock=0,status=2, ';
        }else{
            if($exe_num == $data['max_times']){
                $update .= 'status=3,';
            }else{
                $update .= 'status=1,is_lock=0,';
            }
        }
        $update .= 'run_time='.time();
        return $this->execute("update queue set ".$update." where id=".$data['id']);
    }
}