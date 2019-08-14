<?php

class TSI_Yesbycash_Block_Sales_Order_Cancel extends Mage_Core_Block_Template {

    /**
     * Get the current order
     * 
     * @return type
     */
    public function getOrder() {
        return Mage::registry('current_order');
    }

    /**
     * Test if the order is a yesbycash order
     * 
     * @return boolean
     */
    public function isYesByCash() {
        $order = $this->getOrder();
        $barcode = $order->getYesbycashBarcode();
        if (!is_null($barcode)) {
            return true;
        }
        return false;
    }

    /**
     * Test if the order is alreay canceled
     * 
     * @return boolean
     */
    public function isCanceled() {
        $order = $this->getOrder();
        $status = $order->getStatus();
        if ($status == 'canceled') {
            return true;
        }
        return false;
    }

    /**
     * Test if its possible to show or not the button for canceled the order
     * 
     * @return boolean
     */
    public function showButton() {
        if ($this->isYesByCash() && !$this->isCanceled()) {
            return true;
        }
        return false;
    }

    /**
     * Get the Cancel url
     * 
     * @param object $order
     * @return string
     */
    public function getCancelUrl($order) {
        return $this->getUrl('tsi_yesbycash/ipn/cancel', array('order_id' => $order->getId()));
    }

}
