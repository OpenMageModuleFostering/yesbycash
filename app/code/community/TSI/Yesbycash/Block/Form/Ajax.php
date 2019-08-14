<?php

class TSI_Yesbycash_Block_Form_Ajax extends TSI_Yesbycash_Block_Form_Standard {

    /**
     * 
     */
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('yesbycash/payment/form.phtml');
    }

    /**
     * 
     * @return type
     */
    public function getPostcode() {
        return $this->_getRequest()->getParam('postcode');
    }

    /**
     * 
     * @return type
     */
    public function getCountryId() {
        return $this->_getRequest()->getParam('country_id');
    }

}
