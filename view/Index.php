<?php
/**
* 视图
*/
namespace app\view;
class Index
{
	
	function __construct()
	{
		# code...
	}
	public function display($output) {
		// ob_start();
		echo $output;
	}	
}
?>