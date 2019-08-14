<?php

class TSI_Yesbycash_Model_Standard extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'yesbycash_standard';
    protected $_formBlockType = 'tsi_yesbycash/form_standard';
    protected $_infoBlockType = 'tsi_yesbycash/info_standard';

    /**
     * 
     * @return type
     */
    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('tsi_yesbycash/standard/redirect', array('_secure' => true));
    }

}
