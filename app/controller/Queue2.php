<?php
/**
 * 消息队列
 */
namespace app\controller;
use fastphp\base\Controller;
class Queue2 extends Controller
{
    public function __construct(){
    }
    public function index(){
        exec('php /home/wwwroot/test/mvc/index.php /Queue2/task');
    }
    public function task(){
        $count = 0;
        while(true){
            $count++;
            file_put_contents(APP_PATH.'log.txt',$count.PHP_EOL,FILE_APPEND);
            sleep(3);
            if($count >= 10){
                break;
            }
        }
    }
}