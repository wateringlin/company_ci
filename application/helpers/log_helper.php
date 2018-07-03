<?php

if(!function_exists('create_log')){
	class __Log{
		private $monitor = null;
		public function __construct($monitorname){
			$path = APPPATH."/libraries/MonitorLogWRHandle.php";
			if(file_exists($path)){
				require_once($path);
				$sysUser = exec('whoami');
				$this->monitor = new MonitorLogWRHandle($sysUser."_".$monitorname);
			}
		}
		public function append($str,$status = 0){
			if(!is_null($this->monitor)){
				try{
					$this->monitor->append($str,$status);
				}
				catch(Exception $e){
					//
				}
			}
		}
	}
	//创建日志
	function create_log($monitorname){
		return new __Log($monitorname);
	}
}