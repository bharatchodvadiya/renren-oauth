<?php


$config				= new stdClass;

$config->APIURL		= 'http://api.renren.com/restserver.do'; 
	
$config->APIKey		= Mage::getModel('ren/client')->getApiKey('Renren');	
$config->SecretKey	= Mage::getModel('ren/client')->getSecret('Renren');	
$config->APIVersion	= '1.0';	
$config->decodeFormat	= 'json';	

$config->redirecturi= Mage::helper("ren/data")->getConnectUrl();
$config->scope='publish_feed,photo_upload';
?>