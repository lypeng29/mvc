<?php
namespace app\model;
class Model {
	private $data = array(
		'0' => 'id is error',
		'1' => 'Hello furzoom',
		'2' => 'Welcome to furzoom.com',
	);
	public function getData($key) {
		if(in_array($key, array_keys($this->data))){
			return $this->data[$key];
		}else{
			return 'id error!';
		}
	}
	public function addData($data){
		return 1;
	}
}
?>