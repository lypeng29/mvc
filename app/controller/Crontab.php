<?php
/**
 * 消息队列
 */
namespace app\controller;
use fastphp\base\Controller;
use app\model\QueueModel;
// use app\model\ItemModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Crontab extends Controller
{
    public function __construct(){
        $this->_db = new QueueModel();
        // $this->item = new ItemModel();
    }
    public function index(){
        $this->_send();//发送消息类任务
        $this->_url();//执行URL类任务
        $this->_clear();//清除类任务
    }
    //新增示例
    public function addPush(){
        
        // $url = 'http://www.mvc.com/user/upstatus';
        // $this->_db->set_type('url', 'get', $url);

        $params['from'] = '大鹏博客';
        $params['subject'] = '新的留言';
        $params['body'] = '您有新的留言：内容部分！';
        $this->_db->set_type('send', 'mail');
        $this->_db->queuePush($params, 3);
        echo json_encode(array('status'=>'0','message'=>'success'));
        // 必须参数
        // $data['main_type'] = 'url';//类型,例如URL类型，send类型
        // $data['sub_type'] = 'get';//子类型，例如url类型中的get,post | send类型中的mail,sms
        // $data['request_url'] = 'http://xxx';//请求地址，发送类为空
        // $data['params'] = serialize(array('id'=>8));//参数，可以为空
        // $data['add_time'] = time();

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
            // $stime = date('Y-m-d H:i:s',time());
            // $this->item->insert('item',array('item_name'=>$stime));
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
            // $etime = date('Y-m-d H:i:s',time());
            // $this->item->insert('item',array('item_name'=>$etime));
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
        $res = $this->sendemail($mail_data['from'],$mail_data['subject'], $mail_data['body']);
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
    public function sendemail($from='From', $subject='Here is the subject',$body='This is the HTML message body <b>in bold!</b>')
    {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = '893371810@qq.com';                 // SMTP username
            $mail->Password = 'lrxjnkhbjnwmbdeh';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom('893371810@qq.com', $from);
            $mail->addAddress('893371810@qq.com', '风轻云淡');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // $rootPath = dirname(__FILE__);
            // $mail->msgHTML(file_get_contents($rootPath.'/../view/mail.html'));
            $mail->send();
            return true;
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }    
}