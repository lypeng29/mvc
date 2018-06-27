<?php
/**
 * Memcache缓存驱动类
 */

namespace fastphp\cache;

class Memcache implements CacheInterface
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

    }
    /**
     * 清空缓存
     * @return bool
     */
    public function clear()
    {

    }
}