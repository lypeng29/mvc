<?php
namespace app\controller;
class Index
{
	public function __construct(){

	}
	public function index()
	{
		$id = !empty($_GET['id']) ? trim($_GET['id']) : '1';

		//实例化模型
		require(ROOT_PATH.'/model/Model.php');
		$model = new \app\model\Model;
		$data = $model->getData($id);

		//实例化视图
		require(ROOT_PATH.'/view/index.php');
		$view = new \app\view\Index;
		
		$view -> display($data);
	}

	public function add(){
		//实例化模型
		require(ROOT_PATH.'/model/Model.php');
		$model = new \app\model\Model;

		$data['name']='lili';
		$data['age']=26;
		$result = $model->addData($data);
		if($result){
			echo 'success';
		}else{
			echo 'error';
		}
	}
}
?>