<?php

class TSI_Yesbycash_Block_Adminhtml_Sales_order_Creditmemo_Totals extends TSI_Yesbycash_Block_Adminhtml_Sales_Totals {

    protected $_creditmemo;

    public function getCreditmemo() {
        if ($this->_creditmemo === null) {
            if ($this->hasData('creditmemo')) {
                $this->_creditmemo = $this->_getData('creditmemo');
            } elseif (Mage::registry('current_creditmemo')) {
                $this->_creditmemo = Mage::registry('current_creditmemo');
            } elseif ($this->getParentBlock() && $this->getParentBlock()->getCreditmemo()) {
                $this->_creditmemo = $this->getParentBlock()->getCreditmemo();
            }
        }
        return $this->_creditmemo;
    }

    public function getSource() {
        return $this->getCreditmemo();
    }

    /**
     * Initialize creditmemo totals array
     *
     * @return TSI_Yesbycash_Block_Adminhtml_Sales_Totals
     */
    protected function _initTotals() {
        parent::_initTotals();
        $this->addTotal(new Varien_Object(array(
            'code' => 'adjustment_positive',
            'value' => $this->getSource()->getAdjustmentPositive(),
            'base_value' => $this->getSource()->getBaseAdjustmentPositive(),
            'label' => $this->helper('sales')->__('Adjustment Refund')
        )));
        $this->addTotal(new Varien_Object(array(
            'code' => 'adjustment_negative',
            'value' => $this->getSource()->getAdjustmentNegative(),
            'base_value' => $this->getSource()->getBaseAdjustmentNegative(),
            'label' => $this->helper('sales')->__('Adjustment Fee')
        )));
        return $this;
    }

}
