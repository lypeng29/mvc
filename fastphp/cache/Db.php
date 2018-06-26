<?php
/**
 * mysql缓存驱动类
 */

namespace fastphp\cache;

class Db implements CacheInterface
{
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if(empty($options)) {
            $options = array (
                'table'     =>  C('DATA_CACHE_TABLE'),
            );
        }
        $this->options  =   $options;   
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');       
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
        $this->handler   = \fastphp\helper\DbHelper::getInstance();
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name, $default = '') {
        $name       =  $this->options['prefix'].addslashes($name);
        // N('cache_read',1);
        $result     =  $this->handler->query('SELECT `data` FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\' AND (`expire` =0 OR `expire`>'.time().') LIMIT 0,1');
        // $result     =  $this->handler->query('SELECT `data`,`datacrc` FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\' AND (`expire` =0 OR `expire`>'.time().') LIMIT 0,1');
        if(false !== $result ) {
            $result   =  $result[0];
            // if(C('DATA_CACHE_CHECK')) {//开启数据校验
            //     if($result['datacrc'] != md5($result['data'])) {//校验错误
            //         return false;
            //     }
            // }
            $content   =  $result['data'];
            // if(C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
            //     //启用数据压缩
            //     $content   =   gzuncompress($content);
            // }
            $content    =   unserialize($content);
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value,$expire=null) {
        $data   =  serialize($value);
        $name   =  $this->options['prefix'].addslashes($name);
        // N('cache_write',1);
        // if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
        //     //数据压缩
        //     $data   =   gzcompress($data,3);
        // }
        // if(C('DATA_CACHE_CHECK')) {//开启数据校验
        //     $crc  =  md5($data);
        // }else {
        //     $crc  =  '';
        // }
        $crc  =  '';
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $expire	    =   ($expire==0)?0: (time()+$expire) ;//缓存有效期为0表示永久缓存
        $result     =   $this->handler->query('select `cachekey` from `'.$this->options['table'].'` where `cachekey`=\''.$name.'\' limit 0,1');
        if(!empty($result) ) {
        	//更新记录
            $result  =  $this->handler->execute('UPDATE '.$this->options['table'].' SET data=\''.$data.'\' ,datacrc=\''.$crc.'\',expire='.$expire.' WHERE `cachekey`=\''.$name.'\'');
        }else {
        	//新增记录
             $result  =  $this->handler->execute('INSERT INTO '.$this->options['table'].' (`cachekey`,`data`,`datacrc`,`expire`) VALUES (\''.$name.'\',\''.$data.'\',\''.$crc.'\','.$expire.')');
        }
        if($result) {
            // if($this->options['length']>0) {
            //     // 记录缓存队列
            //     $this->queue($name);
            // }
            return true;
        }else {
            return false;
        }
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function delete($name) {
        $name  =  $this->options['prefix'].addslashes($name);
        return $this->handler->execute('DELETE FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\'');
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        return $this->handler->execute('TRUNCATE TABLE `'.$this->options['table'].'`');
    }
}