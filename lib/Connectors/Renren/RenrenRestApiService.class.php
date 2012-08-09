<?php

require_once 'HttpRequestService.class.php';
//require_once 'config.inc.php'; 

 class RenrenRestApiService extends HttpRequestService{

	private $_config;
	private	$_postFields	= '';
	private $_params		=	array();
	private $_currentMethod;
	private static $_sigKey = 'sig';
	private	$_sig			= '';
	private $_call_id		= '';
	
	private $_keyMapping	= array(
				'api_key'	=>	'',
				'method'	=>	'',
				'v'			=>	'',
				'format'	=>	'',
			);
	
	public function __construct(){
		global $config;
		
		$config				= new stdClass;
		
		$config->APIURL		= 'http://api.renren.com/restserver.do';
		
		$config->APIKey		= Mage::getModel('ren/client')->getApiKey('Renren');
		$config->SecretKey	= Mage::getModel('ren/client')->getSecret('Renren');
		$config->APIVersion	= '1.0';
		$config->decodeFormat	= 'json';
		
		$config->redirecturi= Mage::helper("ren/data")->getConnectUrl();
		$config->scope='publish_feed,photo_upload';
		
		parent::__construct();
		
		$this->_config = $config;
		
		if(empty($this->_config->APIURL) || empty($this->_config->APIKey) || empty($this->_config->SecretKey)){
			throw new exception('Invalid API URL or API key or Secret key, please check config.inc.php');
		}

	}

    
	public function GET(){

		$args = func_get_args();
		$this->_currentMethod	= trim($args[0]); #Method
		$this->paramsMerge($args[1])
			 ->getCallId()
			 ->setConfigToMapping()
			 ->generateSignature();

		#Invoke
		unset($args);

		return $this->_GET($this->_config->APIURL, $this->_params);
	
	}

    
	public function rr_post_curl(){

		$args = func_get_args();
		$this->_currentMethod	= trim($args[0]); #Method
		$this->paramsMerge($args[1])
			 ->getCallId()
			 ->setConfigToMapping()
			 ->generateSignature();

		#Invoke
		unset($args);

		return $this->_POST($this->_config->APIURL, $this->_params);
	
	}
    
	private function generateSignature(){
			$arr = array_merge($this->_params, $this->_keyMapping);
			ksort($arr);
			reset($arr);
			$str = '';
			foreach($arr AS $k=>$v){
				$v=$this->convertEncoding($v,$this->_encode,"utf-8");
				$arr[$k]=$v;
				$str .= $k.'='.$v;
			}
			
			$this->_params = $arr;
			$str = md5($str.$this->_config->SecretKey);
			$this->_params[self::$_sigKey] = $str;
			$this->_sig = $str;

			unset($str, $arr);

			return $this;
	}

	
	private function paramsMerge($params){
		$this->_params = $params;
		return $this;
	}

    
	private function setConfigToMapping(){
			$this->_keyMapping['api_key']	= $this->_config->APIKey;
			$this->_keyMapping['method']	= $this->_currentMethod;
			$this->_keyMapping['v']			= $this->_config->APIVersion;
			$this->_keyMapping['format']	= $this->_config->decodeFormat;
		return $this;
	}

	private function setAPIURL($url){
			$this->_config->APIURL = $url;
	}

  
	public function getCallId(){
		$this->_call_id = str_pad(mt_rand(1, 9999999999), 10, 0, STR_PAD_RIGHT);
		return $this;
	}
	
	public function rr_post_fopen(){

		$args = func_get_args();
		$this->_currentMethod	= trim($args[0]); #Method
		$this->paramsMerge($args[1])
			 ->getCallId()
			 ->setConfigToMapping()
			 ->generateSignature();

		#Invoke
		unset($args);

		return $this->_POST_FOPEN($this->_config->APIURL, $this->_params);
	
	}
	public function rr_photo_post_fopen(){

		$args = func_get_args();
		$this->_currentMethod	= trim($args[0]); #Method
		$this->paramsMerge($args[1])
			 ->getCallId()
			 ->setConfigToMapping()
			 ->generateSignature();

		#Invoke
		$photo_files=$args[2];

		unset($args);
		return $this->_photoUpload($this->_config->APIURL, $this->_params,$photo_files);
	
	}
	
	
 }
?>