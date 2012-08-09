<?php
class Social_Ren_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
    	if(isset($_REQUEST['connect']))
    	{
    		
    		//$email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
    		//Mage::getModel('ren/client')->setUser($email,'Renren',$_REQUEST['id'],$_REQUEST['rname'],$_REQUEST);
    		$this->_redirect('customer/social');
    	}
    	elseif(isset($_REQUEST['connecterror']))
    	{
    		    		
    		$message='Your connection is already being used by &lt;'.$_REQUEST['user'].'&gt; [with identity &lt;'.$_REQUEST['identity'].'&gt;]';
    		Mage::getSingleton('core/session')->addError($message);
    		$this->_redirect('customer/social');
    		return;
    	}
    	elseif(isset($_REQUEST['code']))
    	{
    		Mage::getModel('ren/client')->oauthuser($_REQUEST);
    	}
    	else
    	{
    		if(isset($_REQUEST['data']))
    		{
    			$identityid = $_REQUEST['mid'];
    			$email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
    			Mage::getModel('ren/client')->setDisconnectConnector($email,'Renren',$identityid);
    			$this->_redirect('customer/social');
    		}
    		else
    		{
    			if(isset($_REQUEST['mid']))
    			{
    				$mid = $_REQUEST['mid'];
    				Mage::getSingleton('core/session')->setIdentityId($mid);
    			}
    			
    			$url=Mage::getModel('ren/client')->login();
    			$this->_redirectUrl($url);
    		}
    	}
	  
    }
}