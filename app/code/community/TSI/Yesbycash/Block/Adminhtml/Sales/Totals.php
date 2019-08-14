<?php

class TSI_Yesbycash_Block_Adminhtml_Sales_Totals extends Mage_Adminhtml_Block_Sales_Totals {

    /**
     * Initialize order totals array
     *
     * @return TSI_Yesbycash_Block_Adminhtml_Sales_Totals
     */
    protected function _initTotals() {
        parent::_initTotals();

        $source = $this->getSource();

        $this->_totals['paid'] = new Varien_Object(array(
            'code' => 'paid',
            'strong' => true,
            'value' => $this->getSource()->getTotalPaid(),
            'base_value' => $this->getSource()->getBaseTotalPaid(),
            'label' => $this->helper('sales')->__('Total Paid'),
            'area' => 'footer'
        ));
        $this->_totals['refunded'] = new Varien_Object(array(
            'code' => 'refunded',
            'strong' => true,
            'value' => $this->getSource()->getTotalRefunded(),
            'base_value' => $this->getSource()->getBaseTotalRefunded(),
            'label' => $this->helper('sales')->__('Total Refunded'),
            'area' => 'footer'
        ));
        $this->_totals['due'] = new Varien_Object(array(
            'code' => 'due',
            'strong' => true,
            'value' => $this->getSource()->getTotalDue(),
            'base_value' => $this->getSource()->getBaseTotalDue(),
            'label' => $this->helper('sales')->__('Total Due'),
            'area' => 'footer'
        ));

        $totals = $this->_totals;
        $newTotals = array();
        if (count($totals) > 0) {
            foreach ($totals as $index => $arr) {
                if ($index == "grand_total") {
                    if (((float) $this->getSource()->getYesbycashFee()) != 0) {
                        $label = $this->__('Frais de service Yesbycash');
                        $newTotals['yesbycash_fee'] = new Varien_Object(array(
                            'code' => 'yesbycash_fee',
                            'field' => 'yesbycash_fee',
                            'base_value' => $source->getBaseYesbycashFee(),
                            'value' => $source->getYesbycashFee(),
                            'label' => $label
                        ));
                    }
                }
                $newTotals[$index] = $arr;
            }
            $this->_totals = $newTotals;
        }

        return $this;
    }

}
