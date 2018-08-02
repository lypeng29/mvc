<?php
namespace app\controller;
use fastphp\base\Controller;
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


}