<?php

class TSI_Yesbycash_Model_Source_Yesbycashfeetype {

    /**
     * 
     * @return type
     */
    public function toOptionArray() {
        return array(
            array('value' => 'yesbycash_fee_type_currency', 'label' => Mage::helper('tsi_yesbycash')->__('En monnaie actuelle')),
            array('value' => 'yesbycash_fee_type_percent', 'label' => Mage::helper('tsi_yesbycash')->__('En pourcentage'))
        );
    }

}
