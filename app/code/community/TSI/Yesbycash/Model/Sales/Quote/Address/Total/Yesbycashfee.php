<?php

class TSI_Yesbycash_Model_Sales_Quote_Address_Total_Yesbycashfee extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    /**
     * 
     */
    public function __construct() {
        $this->setCode('yesbycash_fee');
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
     * @param Mage_Sales_Model_Quote_Address $address
     * @return \TSI_Yesbycash_Model_Sales_Quote_Address_Total_Yesbycashfee
     */
    public function collect(Mage_Sales_Model_Quote_Address $address) {
        $address->setYesbycashFee(0);
        $address->setBaseYesbycashFee(0);

        if (!$this->_getHelper()->getStoreConfig('yesbycash_fee')) {
            return $this;
        }

        $items = $address->getAllItems();
        if (!count($items)) {
            return $this;
        }

        $paymentMethod = $address->getQuote()->getPayment()->getMethod();

        $tax = 0;
        if (Mage::getStoreConfig('tax/calculation/price_includes_tax') != 1) {
            $tax = $address->getTaxAmount();
        }

        if ($paymentMethod) {
            $baseAmount = $this->_getHelper()->getYesbycashFee($address->getQuote());
            $amount = Mage::helper('core')->currency($baseAmount, true, false);
            $address->setYesbycashFee($amount);
            $address->setBaseYesbycashFee($baseAmount);
        }

        $address->setGrandTotal($address->getGrandTotal() + $tax + $address->getYesbycashFee());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $tax + $address->getBaseYesbycashFee());

        return $this;
    }

    /**
     * 
     * @param Mage_Sales_Model_Quote_Address $address
     * @return type
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amount = $address->getYesbycashFee();
        if (($amount != 0)) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('tsi_yesbycash')->__('Frais de service Yesbycash'),
                'full_info' => array(),
                'value' => $amount,
                'base_value' => $amount
            ));
        }
        return $amount;
    }

}
