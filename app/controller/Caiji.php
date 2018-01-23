<?php
namespace app\controller;
use fastphp\base\Controller;
use QL\QueryList;
class Caiji extends Controller
{
    public function index()
    {
        $hj = QueryList::Query('http://mobile.csdn.net/',array("title"=>array('.unit h1','text')));
        print_r($hj->data);
    }
}