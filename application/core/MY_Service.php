<?php if (!defined('BASEPATH')) exit('No direct access allowed.');

class MY_Service{
	protected $facades = array();
	protected $facade_paths = array();
	public function __construct(){
		//log_message('debug','Service Iniitialized');
		$this->facade_paths[] =  APPPATH;
	}
	function __get($key){
		$CI = &get_instance();
		return $CI->$key;
	}
	protected function proxy($facade,$params = null){
		if(!is_null($params) && !is_array($params)){
			$params = null;
		}
		$subdir = '';
		if(($last_slash = strpos($facade, '/')) !== false){
			$subdir = substr($facade, 0,$last_slash + 1);
			$facade =  substr($facade, $last_slash + 1);
		}
		if(array_key_exists($facade, $this->facades)){
			return $this->facades[$facade];
		}
		foreach ($this->facade_paths as $path) {
			$file_path = $path."facades/".$subdir.$facade.'.php';
			if(!file_exists($file_path)){
				continue;
			}
			include_once($file_path);
			if($params == null){
				$obj = new $facade();
			}
			else{
				$obj = new $facade($params);
			}
			$target = new __Delegate($obj,$facade);
			$this->facades[$facade] = $target;
			return $target;
		}
	}
}
