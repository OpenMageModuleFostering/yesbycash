<?php

class TSI_Yesbycash_Block_Onepage_Failure extends Mage_Checkout_Block_Onepage_Failure {

    /**
     * 
     * @return type
     */
    public function getOrderId() {
        return $this->getRealOrderId();
    }

    /**
     * 
     * @return type
     */
    public function getOrder() {
        return Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
    }

    /**
     * 
     * @return type
     */
    public function getPayment() {
        return $this->getOrder()->getPayment()->getMethod();
    }

}
