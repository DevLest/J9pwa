<?php
    //PHPCMS框架路径
   define('PC_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
   //CHARSET
   define('CHARSET', 'utf-8');
   //RPC引用地址
   define('PHPRPC_URL','http://j9adminaxy235.32sun.com/phprpc/');
   define('PHPRPC_Normal','http://j9adminaxy235.32sun.com/phprpc/server.php');
   define('FOREGROUND_URL','http://j9adminaxy235.32sun.com/phprpc/foreground.php');
   define('QUERY_URL','http://j9adminaxy235.32sun.com/phprpc/mysql_query.php');
   define('SERVER_URL','http://j9adminaxy235.32sun.com/phprpc/server.php');
   define('GAME_PRE','yb');
   //缓存文件夹地址
  define('CACHE_PATH', PC_PATH.'caches'.DIRECTORY_SEPARATOR);
	/**
	 * 加载模板
	 * @param string $file 文件名
	 * @param string $m 模型名
	 */
	function admin_tpl($file, $m = '') {
		$m = empty($m) ? '' : $m;
		//if(empty($m)) return false;
		return PC_PATH.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$file.'.tpl.php';
	}
	
	/**
	 * 加载类
	 * @param string $file 文件名
	 * @param string $m 模型名
	 */
	function load_class($cname) {
		//$m = empty($m) ? '' : $m;
		//if(empty($m)) return false;
		return PC_PATH.'class'.DIRECTORY_SEPARATOR.$cname.'.class.php';
	}
	
	/**
	 * 加载function
	 * @param string $file 文件名
	 * @param string $m 模型名
	 */
	function load_function($fname) {
		return PC_PATH.'functions'.DIRECTORY_SEPARATOR.$fname.'.fun.php';
	}
?>