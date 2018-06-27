<?php
namespace fastphp;

/**
 * 缓存管理类
 */
class Cache{
    /**
     * 操作句柄
     * @var string
     * @access protected
     */
    protected $handler    ;

    /**
     * 缓存连接参数
     * @var integer
     * @access protected
     */
    protected $options = array();

    /**
     * 连接缓存
     * @access public
     * @param string $type 缓存类型
     * @param array $options  配置数组
     * @return object
     */
    public function connect($type='',$options=array()) {
        if(empty($type))  $type = C('DATA_CACHE_TYPE');
        $class  =   strpos($type,'\\')? $type : 'fastphp\\cache\\'.ucwords(strtolower($type));            
        if(class_exists($class))
            $cache = new $class($options);
        else
            // E(L('_CACHE_TYPE_INVALID_').':'.$type);
            die('cache type '.$type.' not exist!');
        return $cache;
    }
    /**
     * 取得缓存类实例
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance($type='',$options=array()) {
		static $_instance	=	array();
		$guid	=	$type.to_guid_string($options);
		if(!isset($_instance[$guid])){
			$obj	=	new Cache();
			$_instance[$guid]	=	$obj->connect($type,$options);
		}
		return $_instance[$guid];
    }  
    // public function __call($method,$args){
    //     //调用缓存类型自己的方法
    //     if(method_exists($this->handler, $method)){
    //        return call_user_func_array(array($this->handler,$method), $args);
    //     }else{
    //         // E(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
    //         die('method not exist!');
    //         return;
    //     }
    // }
}

?>