<?php
/**
 * 文件缓存驱动类
 */

namespace fastphp\cache;

class File implements CacheInterface
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
        if ($expiry > 0) {
            $expiry = time() + $expiry;
        }

        $data = [
            'expiry' => $expiry,
            'value' => $value
        ];

        $file = $this->getFile($name);
        $data = var_export($data, true);
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
        if (!is_array($data) || ($data['expiry'] > 0 && $data['expiry'] < time())) {
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
        $file = DIR_CACHE . "/".md5($name).".php";
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
}