<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\ItemModel;
use fastphp\helper\ApiHelper;
// use fastphp\helper\CacheHelper; //文件缓存
use fastphp\Cache;
use fastphp\helper\UploadHelper;
// use fastphp\helper\MyRedisHelper;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
class Index extends Controller
{
    function __construct(){
        // $this->cache = new CacheHelper(20,'cache/');
    }

    public function index(){
        echo 'hello world!';
        
        // L('nihaoa', 2);
        // L(array('AGE' => 20, 'NAME'=>'HUWEI'), 1);
        // var_dump($_GET);
        // var_dump($_POST);
        // var_dump($_SERVER);
    
        $mem = new \Memcache;
        $mem->connect("127.0.0.1", 11211);
        $mem->set('key', 'This is a test!', 0, 60);
        $val = $mem->get('key');
        echo $val;
    }

    public function mycache(){
        $c = Cache::getInstance();
        // $c = Cache::getInstance('File',array('prefix'=>'admin_','expire'=>0));
        var_dump($c->set('user4','zifuchuan4'));
        echo "cache user4 is: ".$c->get('user4');
    }

    public function myredis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        
        var_dump($redis->info());

        if(empty($redis->get('user_id'))){
            echo 'user_id is null <hr/>';
            $redis->set('user_id', 102, 10);
        }
        echo 'user_id is '.$redis->get('user_id');
        echo '<hr/>';
        echo 'expire is '.$redis->ttl('user_id');
    }
    public function myupload(){
        // var_dump($_FILES);
        $upload = new UploadHelper('file');
        $res = $upload->upload_file();  
        var_dump($res);
    }

    public function myuphtml(){
        $html = <<<eof
        <!doctype html>
        <html>
            <head>
                <meta charset="utf-8"/>
                <title>file upload</title>
            </head>
            <body>
            <form name="up" action="/index/myupload" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit">
            </form>
            </body>
        </html>
eof;
        echo $html;
    }

    public function mycachehelper(){
        $this->cache = new CacheHelper(20,'cache/');
        $this->cache->put('nihao','888');
        echo $this->cache->get('nihao');
    }

    public function sendemail()
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
            $mail->setFrom('893371810@qq.com', 'Mailer');
            $mail->addAddress('893371810@qq.com', 'Joe User');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $rootPath = dirname(__FILE__);
            $mail->msgHTML(file_get_contents($rootPath.'/../view/mail.html'));
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }
}