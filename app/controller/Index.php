<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\ItemModel;
use fastphp\helper\ApiHelper;
use fastphp\helper\CacheHelper;
use fastphp\helper\UploadHelper;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Index extends Controller
{
    function __construct(){
        $this->cache = new CacheHelper(20,'cache/');
    }

    public function index(){
        echo 'hello world!';
        var_dump($_GET);
        var_dump($_POST);
        var_dump($_SERVER);
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

    public function mycache(){
        // $this->cache = new CacheHelper(20,'cache/');
        // $this->cache->put('nihao','888');
        $ni = $this->cache->get('nihao');
        echo $ni;
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
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }
}