<?php
namespace fastphp\base;
use fastphp\helper\Db;
class Model extends Db
{
    // public $_db;
    public function __construct()
    {
        $config=array(
            'host'       => DB_HOST,
            'user'       => DB_USER,
            'pass'       => DB_PASS,
            'port'       => DB_PORT,
            'db'         => DB_NAME,
            'charset'    => 'utf8',
        );
        // new Db($config);
        $this->connect($config);
        // $this->_db = Db::getIntance($config);
        // return $this;
    }
    function __destruct() {
    
    }
    // public function init(){
    //     return $this->_db;
    // }
    // public function start(){
    //     $this->result = $this->_db->select('item');
    //     return $this->result;
    // }
}