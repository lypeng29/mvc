<?php
/**
 * Redis缓存驱动类
 */

namespace fastphp\cache;

class Redis implements CacheInterface
{
    /**
     * 设置缓存
     * @param $name
     * @param $value
     * @param int $expiry 有效时间（秒）
     * @return mixed
     */
    public function set($name, $value, $expiry = 0)
    {

    }

    /**
     * 获取缓存
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = '')
    {

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
        deldir(DIR_CACHE . '/')
        return true;
    }
}