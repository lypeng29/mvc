<?php
/**
 * 缓存接口
 */
namespace fastphp\cache;

interface CacheInterface
{
    /**
     * 添加缓存
     * @param $name string 缓存名称
     * @param $value mixed 缓存数据
     * @param int $expiry 截止时间（时间戳）
     * @return mixed
     */
    public function set($name, $value, $expiry = 0);

    /**
     * 获取缓存
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = '');

    /**
     * 删除缓存
     * @param $name
     * @return bool
     */
    public function delete($name);
}
