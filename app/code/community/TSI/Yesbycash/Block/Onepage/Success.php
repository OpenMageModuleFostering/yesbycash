<?php

class TSI_Yesbycash_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success {

    /**
     * 
     * @return type
     */
    public function getOrderId() {
        return $this->_getData('order_id');
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
