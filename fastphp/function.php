<?php
    /**
     * 记录日志，方便调试
     * $word可以是字符串，数组等等
     * 默认文件位置：data/logs/YYYYmmdd.txt
     */
    function L($word='', $level='INFO', $file='') {
        switch ($level) {
            case 1:
                $outlevel = 'WARNING';
                break;
            case 2:
                $outlevel = 'ERROR';
                break;
            default:
                $outlevel = $level;
                break;
        }
        if(empty($file)){
            if(!is_dir(DIR_ROOT.'/data/logs/')){
                mkdir(DIR_ROOT.'/data/logs/',0777);
            }
            $file = DIR_ROOT.'/data/logs/'.strftime("%Y%m%d",time()).'.txt';
        }
        $fp = fopen($file,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp, strftime("%Y-%m-%d %H:%M:%S",time()).' '.$outlevel.': '.var_export($word, true).PHP_EOL);
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
    /**
     * 删除目录下面所有文件，保留目录
     */
	function deldir($dir){
		//删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)){
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;		
				if(!is_dir($fullpath)){
					unlink($fullpath);
				}else{
					deldir($fullpath);
				}
			}
		} 
		closedir($dh);
    }
    /**
     * 获取和设置配置参数 支持批量定义
     * @param string|array $name 配置变量
     * @param mixed $value 配置值
     * @param mixed $default 默认值
     * @return mixed
     */
    function C($name=null, $value=null,$default=null) {
        static $_config = array();
        // 无参数时获取所有
        if (empty($name)) {
            return $_config;
        }
        // 优先执行设置获取或赋值
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                $name = strtoupper($name);
                if (is_null($value))
                    return isset($_config[$name]) ? $_config[$name] : $default;
                $_config[$name] = $value;
                return null;
            }
            // 二维数组设置和获取支持
            $name = explode('.', $name);
            $name[0]   =  strtoupper($name[0]);
            if (is_null($value))
                return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
            $_config[$name[0]][$name[1]] = $value;
            return null;
        }
        // 批量设置
        if (is_array($name)){
            $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
            return null;
        }
        return null; // 避免非法参数
    }
    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param mixed $mix 变量
     * @return string
     */
    function to_guid_string($mix) {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }
?>