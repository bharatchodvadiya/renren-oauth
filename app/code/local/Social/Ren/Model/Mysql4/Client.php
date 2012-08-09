<?php
    class Social_Ren_Model_Mysql4_Client extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("ren/client", "tablename_id");
        }
    }
	 