<?php

class Social_Ren_Model_Client extends Social_Connectors_Model_Abstract implements Social_Connectors_Model_Interface
{
public function setConnectorName()
    {
    	$connector_name='Ren';
    	return $connector_name;
    }
	public function showdata()
	{
		echo "hello from Renren";

	}
	public function logout()
	{
	
	}
    public function login()
    {
    	$config				= new stdClass;
    	
    	$config->APIURL		= 'http://api.renren.com/restserver.do'; 
    	$config->APPID		= '188504';	
    	
    	$config->APIKey		= parent::getApiKey('RenRen');
    	$config->SecretKey	= parent::getSecret('RenRen');
    	$config->APIVersion	= '1.0';	
    	$config->decodeFormat	= 'json';	
    	
    	$config->redirecturi= Mage::helper("ren/data")->getConnectUrl();
    	$config->scope='publish_feed,photo_upload';
    	
    	header('Location: https://graph.renren.com/oauth/authorize?client_id='.$config->APPID.'&response_type=code&scope='.$config->scope.'&state=a%3d1%26b%3d2&redirect_uri='.$config->redirecturi.'&x_renew=true');
    	exit(0);
    	
    }
    public function oauthuser($REQUEST)
    {
    	session_start();
    	$identityid=Mage::getSingleton('core/session')->getIdentityId();
    	
    	$code = $_GET["code"];
    	
    	$config				= new stdClass;
    	 
    	$config->APIURL		= 'http://api.renren.com/restserver.do';
    	$config->APPID		= '188504';
    	
    	$config->APIKey		= parent::getApiKey('Renren');
    	$config->SecretKey	= parent::getSecret('Renren');
    	$config->APIVersion	= '1.0';
    	$config->decodeFormat	= 'json';
    	 
    	$config->redirecturi= Mage::helper("ren/data")->getConnectUrl();
    	$config->scope='publish_feed,photo_upload';
    	
		require_once(Mage::getBaseDir('lib') . '/Connectors/Renren/RenrenOAuthApiService.class.php');
		require_once(Mage::getBaseDir('lib') . '/Connectors/Renren/RenrenRestApiService.class.php');
		
		if($code)
		{
			$oauthApi = new RenrenOAuthApiService;
			
			$post_params = array('client_id'=>$config->APIKey,
					'client_secret'=>$config->SecretKey,
					'redirect_uri'=>$config->redirecturi,
					'grant_type'=>'authorization_code',
					'code'=>$code
			);
			$token_url='http://graph.renren.com/oauth/token';
			$access_info=$oauthApi->rr_post_curl($token_url,$post_params);
					
		     $access_token=$access_info["access_token"];
		     $expires_in=$access_info["expires_in"];
			
			$refresh_token=$access_info["refresh_token"];
			
			$data['oauth_token']=$access_token;
			$data['oauth_token_secret']=$refresh_token;
			$_SESSION["access_token"]=$access_token;
			
			$restApi = new RenrenRestApiService;
			$params = array('fields'=>'uid,name,sex,birthday,mainurl,hometown_location,university_history,tinyurl,headurl','access_token'=>$access_token);
			$res = $restApi->rr_post_curl('users.getInfo', $params);
		
			//print_r($res);
			$gender = $res[0]["sex"];
			$city = $res[0]["hometown_location"];
			$cityname = $city["city"];
		
			 $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
			 $userId = $res[0]["uid"];
		     $username = $res[0]["name"];
		     $profileimage = $res[0]["tinyurl"];
		   		     
			 $birthday = $res[0]["birthday"];
			// Mage::getModel('ren/client')->setUser($email,$identityid,'Renren',$userId,$username,$access_token,$refresh_token,$profileimage,$gender,$cityname);
			 $rurl= Mage::helper('ren/data')->getConnectUrl();
			
		}
		$getdata= Mage::getModel('ren/client')->checkConnected($userId,'Renren',$identityid);
		//print_r($getdata);
		
		if($getdata)
		{
			$user = $getdata['user_screen_name'];
			$identity = $getdata['identity_id'];
			$identity1 = Mage::getModel('ren/client')->getidentityname($identity);
    	?>
    	<script language="javascript">
    	    	window.opener.location = "<?php echo $rurl.'?connecterror=true&user='.$user.'&identity='.$identity1['f_name']; ?>";
    	    	window.close();
    	</script>
    	<?php
        }
    	else 
    	{
    		Mage::getModel('ren/client')->setUser($email,$identityid,'Renren',$userId,$username,$access_token,$refresh_token,$profileimage,$gender,$cityname);
    	?>
    		<script language="javascript">
    		  //  window.opener.location = "<?php //echo $rurl.'?rname='.$username.'&id='.$userId.'&oauth_token='.$access_token.'&oauth_token_secret='.$refresh_token; ?>";
    		    window.opener.location = "<?php echo $rurl.'?connect=true'; ?>";
    		   	window.close();
    		</script>
    		<?php 
    	}
     }

}
	 