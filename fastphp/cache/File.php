<?php
/**
 * 文件缓存驱动类
 */

namespace fastphp\cache;

class File implements CacheInterface
{
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        $this->options  =   $options;   
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');       
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
    }
    /**
     * 设置缓存
     * @param $name
     * @param $value
     * @param int $expire 有效时间（秒）
     * @return mixed
     */
    public function set($name, $value, $expire = NULL)
    {        
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $expire	    =   ($expire==0)?0: (time()+$expire) ;//缓存有效期为0表示永久缓存

        $data = [
            'expire' => $expire,
            'value' => $value
        ];

        $file = $this->getFile($name);
        $data = var_export($data, true);
        // echo $data;
        // exit();
        file_put_contents($file, "<?php \n return {$data};");
    }

    /**
     * 获取缓存
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = '')
    {
        $file = $this->getFile($name);
        if (!is_file($file)) {
            return $default;
        }
        $data = require "$file";
        if (!is_array($data) || ($data['expire'] > 0 && $data['expire'] < time())) {
            $this->delete($name);
            return $default;
        }
        return $data['value'];
    }

    /**
     * 获取缓存文件名称
     * @param $name
     * @return string
     */
    private function getFile($name)
    {
        $file = DIR_CACHE . "/".$this->options['prefix'].addslashes($name).".php";
        return $file;
    }

    /**
     * 删除缓存
     * @param $name
     * @return bool
     */
    public function delete($name)
    {
        $file = $this->getFile($name);
        if (is_file($file)) {
            return unlink($file);
        }
        return false;
    }
    /**
     * 清空缓存
     * @return bool
     */
    public function clear()
    {
        deldir(DIR_CACHE . '/');
        return true;
    }
}