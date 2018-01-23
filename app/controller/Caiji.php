<?php
/**
 * 本文件的目的，旨在练习vendor目录的文件，是否能正常使用
 */
namespace app\controller;
use fastphp\base\Controller;
use app\model\IfenglistModel;
use QL\QueryList;
class Caiji extends Controller
{
    public function index()
    {
        header("Content-Type:text/html;charset=utf-8");
        $hj = QueryList::Query('http://news.ifeng.com/listpage/11502/0/1/rtlist.shtml',
            array(
                "name"=>array('.newsList a','text'),
                "url"=>array('.newsList a','href')
            )
        );
        echo '正在采集中，请稍候... ...';
        var_dump($hj->data);
        //过滤掉后面三个链接（下一页什么的）
        // $data=array_slice($hj->data,0,60);
        // var_dump($data);
        // exit();
        
        //插入数据表
        // $num = (new IfenglistModel)->addall($data);
        // echo '成功插入'.$num.'行记录';

    }

    public function view(){
        //获取内容；
        echo '内容获取中，请稍候... ...';
    }
}