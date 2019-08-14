<?php

class TSI_Yesbycash_Block_Sales_Order_Totals extends Mage_Sales_Block_Order_Totals {

    /**
     * 
     * @return \TSI_Yesbycash_Block_Sales_Order_Totals
     */
    protected function _initTotals() {
        parent::_initTotals();

        $source = $this->getSource();
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
