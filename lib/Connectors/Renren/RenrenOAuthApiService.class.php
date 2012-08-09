<?php

require_once 'HttpRequestService.class.php';
require_once 'config.inc.php'; #Include configure resources

 class RenrenOAuthApiService extends HttpRequestService{

	private $_config;
	private $_params		=	array();

	
	public function __construct(){
		global $config;
		
		parent::__construct();
		
		$this->_config = $config;

	}

     /**
      * GET wrapper
      * @param method String
      * @param parameters Array
      * @return mixed
      */
	public function GET(){

		$args = func_get_args();
		$this->paramsMerge($args[1])
			 ->generateSignature();
		$reqUrl=$args[0];
		#Invoke
		unset($args);

		return $this->_GET($reqUrl, $this->_params);
	
	}

     /**
      * POST wrapper，基于curl函数，需要支持curl函数才行
      * @param method String
      * @param parameters Array
      * @return mixed
      */
	public function rr_post_curl(){

		$args = func_get_args();
		$this->paramsMerge($args[1])
			 ->generateSignature();
		$reqUrl=$args[0];
		#Invoke
		unset($args);

		return $this->_POST($reqUrl, $this->_params);
	
	}
     /**
      * Generate signature for sig parameter
      * @param method String
      * @param parameters Array
      * @return RenRenClient
      */
	private function generateSignature(){
			$arr = $this->_params;
			foreach($arr AS $k=>$v){
				$v=$this->convertEncoding($v,$this->_encode,"utf-8");
				$arr[$k]=$v;//转码，你懂得
			}
			
			$this->_params = $arr;

			unset($str, $arr);

			return $this;
	}
	private function paramsMerge($params){
		$this->_params = $params;
		return $this;
	}
	
	public function rr_post_fopen(){

		$args = func_get_args();
		$this->paramsMerge($args[1])
			 ->generateSignature();
		$reqUrl=$args[0];

		#Invoke
		unset($args);

		return $this->_POST_FOPEN($reqUrl, $this->_params);
	
	}
	
	
	
 }
?>