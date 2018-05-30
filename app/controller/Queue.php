<?php
/**
 * 消息队列
 */
namespace app\controller;
use fastphp\base\Controller;
use app\model\QueueModel;
use app\model\ItemModel;
class Queue extends Controller
{
    public function __construct(){
        $this->_db = new QueueModel();
        $this->item = new ItemModel();
    }
    public function index(){
        // $this->_send();//发送消息类任务
        $this->_url();//执行URL类任务
        $this->_clear();//清除类任务
    }
    //新增示例
    public function addPush(){
        
        $url = 'http://www.mvc.com/user/upstatus';
        $params['id'] = 1;
        $this->_db->set_type('url', 'get', $url);
        $this->_db->queuePush($params, 3);

    }

    //发送消息
    private function _send(){
        $queue_list = $this->_db->queuePull('send');
        //var_dump($queue_list);die;
        if(!empty($queue_list)){
            foreach($queue_list as $v){
                switch ($v['sub_type']){
                    case 'mail':
                        $this->_sendEmail($v);
                        break;
                    case 'sms':
                        $this->_sendSms($v);
                        break;
                }
            }
        }
        return true;
    }

    //执行URL类任务
    private function _url(){
        $queue_list = $this->_db->queuePull('url');
        if(!empty($queue_list)){
            $stime = date('Y-m-d H:i:s',time());
            $this->item->insert('item',array('item_name'=>$stime));
            foreach($queue_list as $v){
                switch ($v['sub_type']){
                    case 'get':
                        $this->_urlGet($v);
                        break;
                    case 'post':
                        $this->_urlPost($v);
                        break;
                }
            }
            $etime = date('Y-m-d H:i:s',time());
            $this->item->insert('item',array('item_name'=>$etime));
        }
        return true;
    }

    private function _urlGet($data){
        //请求地址
        $request_url = $data['request_url'];
        //拼接参数
        $url_data = unserialize($data['params']);
        if(is_array($url_data)&&!empty($url_data)){
            $i = 0;
            foreach ($url_data as $key => $value) {
                if($i == 0){
                    $request_url .= '?'.$key.'='.rawurlencode($value);
                }else{
                    $request_url .= '&'.$key.'='.rawurlencode($value);
                }
                $i++;
            }
        }
        //设置锁
        $this->_db->setlock($data);
        //执行请求
        $res = $this->httpGet($request_url);
        //更新执行状态
        return $this->_db->queueUpdate($res, $data);
    }

    private function _urlPost($data){
        //请求地址
        $request_url = $data['request_url'];
        $url_data = unserialize($data['params']);
        if(is_array($url_data)&&!empty($url_data)){
            //设置锁
            $this->_db->setlock($data);
            //执行请求            
            $res = $this->httpPost($request_url, $url_data);
            //更新执行状态
            return $this->_db->queueUpdate($res, $data);
        }else{
            return false;
        }
    }

    //发送邮件
    private function _sendEmail($data){
        $mail_data = unserialize($data['params']);
        if(!is_array($mail_data)){
            return false;
        }
        $res = send_email($mail_data['maillAddress'], $mail_data['subject'], $mail_data['content']);
        //更新执行状态
        return $this->_db->queueUpdate($res, $data);
    }

    //发送短信
    private function _sendSms($data){
        $sms_data = unserialize($data['params']);
        if(!is_array($sms_data)){
            return false;
        }
        $res = sendSms($sms_data['code'], $sms_data['mobile'], $sms_data['params']);
        //更新执行状态
        return $this->_db->queueUpdate($res, $data);
    }
    //定时删除已成功执行的队列
    private function _clear(){
        return $this->_db->clear();
    }
    
    private function httpPost($url, $data) {
        $form_data = "";
        foreach($data as $key => $value) {
            if ($form_data == "") {
                $form_data = $key . "=" . rawurlencode($value);
            } else {
                $form_data = $form_data . "&" . $key . "=" . rawurlencode($value);
            }
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLINFO_HEADER_OUT => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,            
            CURLOPT_POSTFIELDS => $form_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
            )
        ));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);
        if($status['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }

    private function httpGet($url){
        set_time_limit(0);
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLINFO_HEADER_OUT => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,

        ));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);
        if($status['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }
}