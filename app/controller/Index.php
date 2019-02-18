<?php
namespace app\controller;
use fastphp\base\Controller;
class Index extends Controller
{
    public function index(){
        echo 'hello world!<hr/>';

        $manager = new \MongoDB\Driver\Manager("mongodb://lypeng:jobs89757Aa@119.29.52.50:27017");
        $query = new \MongoDB\Driver\Query([], ['limit' => 10]);
    	$cursor = $manager->executeQuery('local.jobs_php', $query);
    	foreach ($cursor as $key => $value) {
    		echo $value->job;
    		echo '<hr/>';
    	}
    	
    }
    public function news(){
    	
		$manager = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		var_dump($manager);
		echo '<hr/>';

		$bulk = new \MongoDB\Driver\BulkWrite();

		$bulk->insert(['_id' => 1, 'x' => 1]);
		$bulk->insert(['_id' => 2, 'x' => 2]);

		$bulk->update(['x' => 2], ['$set' => ['x' => 1]], ['multi' => false, 'upsert' => false]);
		$bulk->update(['x' => 3], ['$set' => ['x' => 3]], ['multi' => false, 'upsert' => true]);
		$bulk->update(['_id' => 3], ['$set' => ['x' => 3]], ['multi' => false, 'upsert' => true]);

		$bulk->insert(['_id' => 4, 'x' => 2]);

		$bulk->delete(['x' => 1], ['limit' => 1]);

		$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 100);
		$result = $manager->executeBulkWrite('local.test', $bulk, $writeConcern);

		printf("Inserted %d document(s)\n", $result->getInsertedCount());
		echo '<hr/>';
		printf("Matched  %d document(s)\n", $result->getMatchedCount());
		echo '<hr/>';
		printf("Updated  %d document(s)\n", $result->getModifiedCount());
		echo '<hr/>';
		printf("Upserted %d document(s)\n", $result->getUpsertedCount());
		echo '<hr/>';
		printf("Deleted  %d document(s)\n", $result->getDeletedCount());

		foreach ($result->getUpsertedIds() as $index => $id) {
		    printf('upsertedId[%d]: ', $index);
		    var_dump($id);
		}
echo '<hr/>';
		/* If the WriteConcern could not be fulfilled */
		if ($writeConcernError = $result->getWriteConcernError()) {
		    printf("%s (%d): %s\n", $writeConcernError->getMessage(), $writeConcernError->getCode(), var_export($writeConcernError->getInfo(), true));
		}
echo '<hr/>';
		/* If a write could not happen at all */
		foreach ($result->getWriteErrors() as $writeError) {
		    printf("Operation#%d: %s (%d)\n", $writeError->getIndex(), $writeError->getMessage(), $writeError->getCode());
		}

		// $con    = new \MongoClient('mongodb://119.29.52.50:27017'); //VERSION >= 1.3.0
		// $con    = new Mongo('mongodb://127.0.0.1:27017'); //VERSION < 1.3.0
		// $db     = $con->selectDB('local');//或者$db=$con->mydb;
		// $col    = $db->jobs_php;
		//获取总数
		// $count = $col->find()->count();
		// echo $count;

// 增加
// //单条插入
// $data = array( 
//   'name' => 'one',
//   'age' => 1
// );
// 插入的时候可以指定_id
// $data = array( 
//     '_id'=>2,
//     'name' => 'two',
//     'age' => 2
// );
// $result=$col->insert($data);
// if($result['ok']){
//     echo 'insert success!';
// }
//批量插入方式一
// $users[]=array('name'=>'li','age'=>20);
// $users[]=array('name'=>'wang','age'=>22);
// $users[]=array('name'=>'liu','age'=>21);
// $users[]=array('name'=>'zhang','age'=>22);
// $result=$col->batchInsert($users);
//批量插入方式二
// $usera=array('name'=>'ma','age'=>22);
// $userb=array('name'=>'han','age'=>21);
// $result=$col->batchInsert(
//     array($usera, $userb),
//     array('continueOnError' => true)
// );
//删除
// var query = db.foo.find().limit(5);
// query.remove();
// db.foo.find().limit(200).remove();
//删除全部符合条件的
//$result = $col->remove( array('age'=> 21 ) );
//只删除一条
//$result = $col->remove( array('age'=> 21 ) , array('justOne' => true));
//修改
// $result = $col->update(
//     array('name'=>'li'),
//     array('$set'=> array('age'=>'23')),
//     array('upsert' => true)
// );
//查询
 // $result = $col->find();
 // $result = $col->find( array('name'=>'li' , 'age'=>'21') );
 // $result = $col->find( array('age'=>array('$gt'=>5,'$lt'=>25)) );
 // $result = $col->find( array('_id'=>2 , 'sub.uid'=>5 ) );

// echo '<hr/>';
//越过多少
//$result = $col->find()->skip(2);
//排序
//$result = $col->find()->sort(array('age' => 1)）;//age asc
//返回字段，find第二个参数
//$result = $col->find( array(), array('content') );
//$result = $col->find( array(), array('content' => 0 ) ); //忽略字段
// $cursor = $col->find(array(),array('age'))->sort(array('age'=>1));
// foreach ($cursor as $document) {
//     // echo $document['_id'] .'  '. $document['age'];
//     echo $document['age'] . '<br/>';
// }    	
    }
}