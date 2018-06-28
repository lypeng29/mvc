<?php
/**
 * Redis缓存驱动类
 */

namespace fastphp\cache;

class Redis implements CacheInterface
{
	 /**
	 * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if ( !extension_loaded('redis') ) {
            exit('not support redis');
        }
        $options = array_merge(array (
            'host'          => C('REDIS_HOST') ? : '127.0.0.1',
            'port'          => C('REDIS_PORT') ? : 6379,
            'timeout'       => C('DATA_CACHE_TIMEOUT') ? : false,
            'persistent'    => false,
        ),$options);

        $this->options =  $options;
        $this->options['expire'] =  isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
        $this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');        
        // $this->options['length'] =  isset($options['length'])?  $options['length']  :   0;        
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        $this->handler  = new \Redis;
        $options['timeout'] === false ?
            $this->handler->$func($options['host'], $options['port']) :
            $this->handler->$func($options['host'], $options['port'], $options['timeout']);
    }
    /**
     * 设置缓存
     * @param $name
     * @param $value
     * @param int $expire 有效时间（秒）
     * @return mixed
     */
    public function set($name, $value, $expire = null)
    {
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire) && $expire) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        // if($result && $this->options['length']>0) {
        //     // 记录缓存队列
        //     $this->queue($name);
        // }
        return $result;
    }

    /**
     * 获取缓存
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = '')
    {
        $value = $this->handler->get($this->options['prefix'].$name);
        if(empty($value)){
            $value = $default;
        }
        $jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 删除缓存
     * @param $name
     * @return bool
     */
    public function delete($name)
    {
        return $this->handler->delete($this->options['prefix'].$name);
    }
    /**
     * 清空缓存
     * @return bool
     */
    public function clear()
    {
        return $this->handler->flushDB();
    }
}