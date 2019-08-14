<?php

class TSI_Yesbycash_Block_Info_Standard extends Mage_Payment_Block_Info {

    /**
     * 
     */
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('yesbycash/payment/info.phtml');
    }

}
