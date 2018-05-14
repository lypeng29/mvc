<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\FinanceModel;
use fastphp\helper\ApiHelper;
class Api extends Controller
{
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