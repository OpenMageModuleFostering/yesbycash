<?php

class TSI_Yesbycash_Block_Form_Standard extends Mage_Payment_Block_Form {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('yesbycash/payment/form.phtml');
    }

    /**
     * Get outlets list with postcode and country id param
     * 
     * @return type
     */
    public function getOutlets() {
        $outlets = $this->getApi()->getOutletsList($this->getPostcode(), $this->getCountryId());
        return $outlets->outlets;
    }

    /**
     * 
     * @return type
     */
    protected function getApi() {
        return Mage::getModel('tsi_yesbycash/api_methods');
    }

    /**
     * 
     * @return type
     */
    protected function _getHelper() {
        return Mage::helper('tsi_yesbycash');
    }

    /**
     * 
     * @return type
     */
    protected function _getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * 
     * @return type
     */
    public function getPostcode() {
        return $this->_getQuote()->getBillingAddress()->getData('postcode');
    }

    /**
     * 
     * @return type
     */
    public function getCountryId() {
        return $this->_getHelper()->getCountryAlpha3($this->_getQuote()->getBillingAddress()->getData('country_id'));
    }

    /**
     * Get allowed country with alpha3 code
     * 
     * @return array
     */
    public function getAllowedCountrys() {
        $allowedCountrys = explode(',', $this->_getHelper()->getAllowedCountrys());
        $newAllowedCountrys = array();
        foreach ($allowedCountrys as $allowedCountry) {
            $countryName = Mage::getModel('directory/country')->load($allowedCountry)->getName();
            $newAllowedCountrys[$this->_getHelper()->getCountryAlpha3($allowedCountry)] = $countryName;
        }
        return $newAllowedCountrys;
    }

    /**
     * Get the Fee for yesbycash method
     * 
     * @return string
     */
    public function getYesbycashFee() {
        if (!$this->_getHelper()->getStoreConfig('yesbycash_fee')) {
            return false;
        }
        $baseAmount = Mage::helper('tsi_yesbycash')->getYesbycashFee();
        $amount = Mage::helper('core')->currency($baseAmount, true, false);
        return $amount;
    }

}
