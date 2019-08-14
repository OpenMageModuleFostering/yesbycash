<?php

class TSI_Yesbycash_Block_Sales_order_Invoice_Totals extends TSI_Yesbycash_Block_Sales_Order_Totals {

    protected $_invoice = null;

    /**
     * 
     * @return type
     */
    public function getInvoice() {
        if ($this->_invoice === null) {
            if ($this->hasData('invoice')) {
                $this->_invoice = $this->_getData('invoice');
            } elseif (Mage::registry('current_invoice')) {
                $this->_invoice = Mage::registry('current_invoice');
            } elseif ($this->getParentBlock()->getInvoice()) {
                $this->_invoice = $this->getParentBlock()->getInvoice();
            }
        }
        return $this->_invoice;
    }

    /**
     * 
     * @param type $invoice
     * @return \TSI_Yesbycash_Block_Sales_order_Invoice_Totals
     */
    public function setInvoice($invoice) {
        $this->_invoice = $invoice;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getSource() {
        return $this->getInvoice();
    }

    /**
     * 
     * @return \TSI_Yesbycash_Block_Sales_order_Invoice_Totals
     */
    protected function _initTotals() {
        parent::_initTotals();
        $this->removeTotal('base_grandtotal');
        return $this;
    }

}
