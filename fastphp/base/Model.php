<?php
namespace fastphp\base;
use fastphp\helper\Dbt;
class Model extends Dbt
{
    public $result;
    public $db;
    public function __construct()
    {
        $config=array(
            'host'       => DB_HOST,
            'user'       => DB_USER,
            'pass'       => DB_PASS,
            'port'       => DB_PORT,
            'db'         => DB_NAME,
            'table'      => 'item',
            'charset'    => 'utf8',
        );
        $this->db = Dbt::getIntance($config);
        // return $this;
    }
    public function init(){
        return $this->db;
    }
    public function start(){
        $this->result = $this->db->getAll();
        return $this->result;
    }
}