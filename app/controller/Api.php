<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\FinanceModel;
use fastphp\helper\ApiHelper;
use fastphp\Cache;
class Api extends Controller
{
    public function init(){
        $this->cache = Cache::getInstance('File',array('prefix'=>'admin_','expire'=>1200));
    }
    public function checklogin(){
        if($_POST['user']=='admin' && $_POST['pass']=='123456'){
            $token = md5(time());
            $this->init();
            $this->cache->set('token',$token);
            ApiHelper::output(array('token'=>$token));
        }else{
            ApiHelper::output(10001,'username or password error!');
        }
    }
    // 获取类别
    // get,直接注入参数，如果有两个参数，($tid,$fid)
    public function getcategory($tid){
        $db = new FinanceModel();
        $data = $db->getcategory($tid);
        ApiHelper::output($data);
    }
    // 添加记录
    // post
    public function addfinance()
    {
        $result = (new FinanceModel)->add($_POST);
        ApiHelper::output(array());
    }
    // 获得数据
    public function getinfo($type=''){
        $result = (new FinanceModel)->getlist($type);
        ApiHelper::output($result);
    }
    public function getsum(){
        $data['v1'] = (new FinanceModel)->getsum('1');
        $data['v2'] = (new FinanceModel)->getsum('2');
        $data['v3'] = (new FinanceModel)->getsum('1',true);//true获取本月的
        $data['v4'] = (new FinanceModel)->getsum('2',true);

        ApiHelper::output($data);
    }
}