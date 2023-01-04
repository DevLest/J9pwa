<?php
define('CACHE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'caches'.DIRECTORY_SEPARATOR);
class cache_file {
	
	/*缓存默认配置*/
	protected $_setting = array(
								'suf' => '.cache.php',	/*缓存文件后缀*/
								'type' => 'array',		/*缓存格式：array数组，serialize序列化，null字符串*/
							);
	
	/*缓存路径*/
	protected $filepath = '';
	
	protected $cache_path = CACHE_PATH;
	
	/**
	 * 构造函数
	 * @param	array	$setting	缓存配置
	 * @return  void
	 */
	public function __construct($setting = '') {
		$this->get_setting($setting);
		//include_once('../base.fun.php');
	}
	
	/**
	 * 写入缓存
	 * @param	string	$name		缓存名称
	 * @param	mixed	$data		缓存数据
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed				缓存路径/false
	 */

	public function set($name, $data, $setting = '', $type = 'data', $module = 'users') {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = 'users';
		$filepath = $this->cache_path.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];
	    if(!is_dir($filepath)) {
			mkdir($filepath, 0777, true);
	    }
	    if($this->_setting['type'] == 'array') {
	    	$data = "<?php\nreturn ".var_export($data, true).";\n?>";
	    } elseif($this->_setting['type'] == 'serialize') {
	    	$data = serialize($data);
	    }
		$file_size = file_put_contents($filepath.$filename, $data);
	    return $file_size ? $file_size : 'false';
	}
	
	/**
	 * 获取缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed	$data		缓存数据
	 */
	public function get($name, $setting = '', $type = 'data', $module = 'users', $filepath_c = "") 
	{
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = 'users';
		$filepath = $this->cache_path.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];

		if (!file_exists($filepath.$filename)) 
		{
			$filepath = $filepath_c.'caches_'.$module.'/caches_'.$type.'/';
			if (!file_exists($filepath.$filename)) return 'false';
		} 

		if($this->_setting['type'] == 'array') 
		{
			$data = @require($filepath.$filename);
		} 
		elseif($this->_setting['type'] == 'serialize') 
		{
			$data = unserialize(file_get_contents($filepath.$filename));
		}
		    
		return $data;
	}
	
	/**
	 * 删除缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  bool
	 */
	public function delete($name, $setting = '', $type = 'data', $module = 'users') {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = 'users';	
		$filepath = $this->cache_path.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];
		if(file_exists($filepath.$filename)) {
			return @unlink($filepath.$filename) ? true : false;
		} else {
			return false;
		}
	}
	
	/**
	 * 和系统缓存配置对比获取自定义缓存配置
	 * @param	array	$setting	自定义缓存配置
	 * @return  array	$setting	缓存配置
	 */
	public function get_setting($setting = '') {
		if($setting) {
			$this->_setting = array_merge($this->_setting, $setting);
		}
	}

	public function cacheinfo($name, $setting = '', $type = 'data', $module = 'users') {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = 'users';
		$filepath = $this->cache_path.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $filepath.$name.$this->_setting['suf'];
		
		if(file_exists($filename)) {
			$res['filename'] = $name.$this->_setting['suf'];
			$res['filepath'] = $filepath;
			$res['filectime'] = filectime($filename);
			$res['filemtime'] = filemtime($filename);
			$res['filesize'] = filesize($filename);
			return $res;
		} else {
			return false;
		}
	}

}

?>