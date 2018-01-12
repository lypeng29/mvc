<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\ItemModel;
class Item extends Controller
{
	public function index()
	{
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

        // if ($keyword) {
        //     $items = (new ItemModel)->search($keyword);
        // } else {
        //     // 查询所有内容，并按倒序排列输出
        //     // where()方法可不传入参数，或者省略
        //     // $items = (new ItemModel)->where()->order(['id DESC'])->fetchAll();
            // $items = (new Model)->start();
            // $items = (new Model)->init();
            // $db = (new Model)->init();
        $m = new ItemModel();
        $items=$m->getlist();
            // $items = $db->getAll();
            // echo (new ItemModel)->query_num();
            var_dump($items);
        // }
        // new ItemModel();
        // (new ItemModel)->search('5');
        // echo(DB_HOST);
        // $this->assign('title', '全部条目');
        // $this->assign('keyword', $keyword);
        // $this->assign('items', $items);
        // $this->render();

		// $id = !empty($_GET['id']) ? trim($_GET['id']) : '1';

		// //实例化模型
		// require(ROOT_PATH.'/model/Model.php');
		// $model = new \app\model\Model;
		// $data = $model->getData($id);

		// //实例化视图
		// require(ROOT_PATH.'/view/index.php');
		// $view = new \app\view\Index;
		
		// $view -> display($data);
	}
    // 查看单条记录详情
    public function detail($id)
    {
        // 通过?占位符传入$id参数
        $item = (new ItemModel)->where(["id = ?"], [$id])->fetch();

        $this->assign('title', '条目详情');
        $this->assign('item', $item);
        $this->render();
    }
    
    // 添加记录，测试框架DB记录创建（Create）
    public function add()
    {
        $data['item_name'] = $_POST['value'];
        $count = (new ItemModel)->add($data);

        $this->assign('title', '添加成功');
        $this->assign('count', $count);
        $this->render();
    }
    
    // 操作管理
    public function manage($id = 0)
    {
        $item = array();
        if ($id) {
            // 通过名称占位符传入参数
            $item = (new ItemModel)->where(["id = :id"], [':id' => $id])->fetch();
        }

        $this->assign('title', '管理条目');
        $this->assign('item', $item);
        $this->render();
    }
    
    // 更新记录，测试框架DB记录更新（Update）
    public function update()
    {
        $data = array('id' => $_POST['id'], 'item_name' => $_POST['value']);
        $count = (new ItemModel)->where(['id = :id'], [':id' => $data['id']])->update($data);

        $this->assign('title', '修改成功');
        $this->assign('count', $count);
        $this->render();
    }
    
    // 删除记录，测试框架DB记录删除（Delete）
    public function delete($id = null)
    {
        $count = (new ItemModel)->delete($id);

        $this->assign('title', '删除成功');
        $this->assign('count', $count);
        $this->render();
    }	
}