<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 代理类
 */
class __Delegate{
	private $target;
	private $className;
	public function __construct($target,$className){
		$this->target = $target;
		$this->className = $className;
	}

	public function __call($name,$args){
		$result = false;
		$error = null;
		$log = create_log($this->className);
		$this->_do_before($log,$name,$args);
		$reflect = new ReflectionClass($this->target);
		if($method = $reflect->getMethod($name)){
			if($method->isPublic() && !$method->isAbstract()){
                try{
					$result =  $method->invokeArgs($this->target, $args);
				}
				catch(Exception $e){
					$error = $e;
				}
            }
            else{
            	$error = "function:{$name} not allowed";
            }
		}
		else{
			$error = "function:{$name} not exist";
		}
		if($error != null){
			$this->_exception($error,$name,$log);
		}
		$this->_do_after($log,$name,$result);
		return $result;
	}
	private function _do_before($log,$name,$args){
		$username = $this->getCurUser();
		$str = "username:{$username};{$this->className}->{$name} before invoke;args:".json_encode($args);
		$log->append($str);
	}
	private function _do_after($log,$name,$result){
		$resultStr = '';
		$username = $this->getCurUser();
		if(is_array($result)){
			$resultStr = count($result);
		}
		else{
			$resultStr = json_encode($result);
		}
		$str = "username:{$username};{$this->className}->{$name} invoked success;result:".$resultStr;
		$log->append($str);
	}
	private function _exception($e,$name,$log){
		$username = $this->getCurUser();
		if(is_string($e)){
			$e = new Exception($e);
		}
		$result = array('message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine());
		$str = "username:{$username};{$this->className}->{$name} invoked error;error:".json_encode($result);
		$log->append($str,1);
		throw $e;
	}
	private function getCurUser(){
		$username = '';
		if(isset($_SESSION['nick'])){
			$username = $_SESSION['nick'];
		}
		return $username;
	}
}
/**
 * @author dickzhou
 */
class MY_Loader extends CI_Loader {
	protected $ci_services = array();
	protected $ci_service_paths = array();
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		load_class('Service','core'); // 这里加载类core中的service类
		$this->ci_service_paths[] = APPPATH;
	}
	/**
	 * 加载服务
	 * @param  [type] $service     [description]
	 * @param  [type] $params      [description]
	 * @param  [type] $object_name [description]
	 * @return [type]              [description]
	 */
	public function service($service,$params = null,$object_name = null){
		if(is_array($service)){
			foreach ($service as $s) {
				$this->service($s,$params);
			}
			return;
		}
		if($service == '' or in_array($service, $this->ci_services)){
			return false;
		}
		if(!is_null($params) && !is_array($params)){
			$params = null;
		}
		$subdir = '';
		if(($last_slash = strpos($service, '/')) !== false){
			$subdir = substr($service, 0,$last_slash + 1);
			$service =  substr($service, $last_slash + 1);
		}
		foreach ($this->ci_service_paths as $path) {
			$file_path = $path."services/".$subdir.$service.'.php';
			if(!file_exists($file_path)){
				echo $file_path;
				continue;
			}
			include_once($file_path);
			$service = ucfirst($service);
			if(empty($object_name)){
				$object_name = $service;
			}
			$CI = &get_instance();
			if($params == null){
				$obj = new $service();
			}
			else{
				$obj = new $service($params);
			}
			$target = new __Delegate($obj,$service);
			$CI->$object_name = $target;
			$this->ci_services[] = $object_name;
			return;
		}
	}
}