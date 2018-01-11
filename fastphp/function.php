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
?>