<?php

class TSI_Yesbycash_Model_Sales_Order_Creditmemo_Total_Yesbycashfee extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {

    /**
     * 
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return \TSI_Yesbycash_Model_Sales_Order_Creditmemo_Total_Yesbycashfee
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
        $creditmemo->setYesbycashFee(0);
        $creditmemo->setBaseYesbycashFee(0);

        $amount = $creditmemo->getOrder()->getYesbycashFee();
        $creditmemo->setYesycashFee($amount);

        $amount = $creditmemo->getOrder()->getBaseYesbycashFee();
        $creditmemo->setBaseYesbycashFee($amount);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $creditmemo->getYesbycashFee());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $creditmemo->getBasePYesbycashFee());

        return $this;
    }

}
