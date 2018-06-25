<?php
namespace app\controller;
use fastphp\base\Controller;
use app\model\ItemModel;
use fastphp\helper\ApiHelper;
class Item extends Controller
{
    public function index()
    {
        $model = new ItemModel;
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if ($keyword) {
            $items = $model->search($keyword);
        } else {
            $items = $model->getlist();
        }
        $this->_view->assign('title', '全部条目');
        $this->assign('keyword', $keyword);
        $this->assign('items', $items);
        $this->render();
    }
    // 查看单条记录详情
    public function detail($id)
    {
        $item = (new ItemModel)->find('*','id='.$id);
        $this->assign('title', '条目详情');
        $this->assign('item', $item);
        $this->render();
    }
    
    // 添加记录
    public function add()
    {
        $data['item_name'] = $_POST['value'];
        $model = new ItemModel();
        $insertId = $model->add($data);
        $this->assign('title', '添加成功');
        $this->assign('count', $insertId);
        $this->render();
    }
    
    // 操作管理，添加or修改
    public function manage($id = 0)
    {
        $item = array();
        if ($id) {
           $item = (new ItemModel)->find('*','id='.$id);
        }
        $this->assign('title', '管理条目');
        $this->assign('item', $item);
        $this->render();
    }
    
    // 更新记录
    public function update()
    {
        $data = array('id' => $_POST['id'], 'item_name' => $_POST['value']);
        $count = (new ItemModel)->update($data,'id='.$_POST['id']);
        $this->assign('title', '修改成功');
        $this->assign('count', $count);
        $this->render();
    }
    
    // 删除记录
    public function delete($id = null)
    {
        $count = (new ItemModel)->delete('id='.$id);
        $this->assign('title', '删除成功');
        $this->assign('count', $count);
        $this->render();
    }	
}