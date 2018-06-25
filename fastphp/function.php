<?php
    /**
     * 记录日志，方便调试
     * $word可以是字符串，数组等等
     * 默认文件位置：dist/wwwroot/logs.txt
     */
    function logs($word='', $file='logs.txt') {
        $fp = fopen($file,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp, var_export($word.' ', true));
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time()));
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string $name 字符串
     * @param integer $type 转换类型
     * @return string
     */
    function parse_name($name, $type=0) {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
?>