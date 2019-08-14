<?php

class TSI_Yesbycash_Model_Sales_Order_Invoice_Total_Yesbycashfee extends Mage_Sales_Model_Order_Invoice_Total_Abstract {

    /**
     * 
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return \TSI_Yesbycash_Model_Sales_Order_Invoice_Total_Yesbycashfee
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
        $invoice->setYesbycashFee(0);
        $invoice->setBaseYesbycashFee(0);

        $amount = $invoice->getOrder()->getYesbycashFee();
        $invoice->setYesbycashFee($amount);

        $amount = $invoice->getOrder()->getBaseYesbycashFee();
        $invoice->setBaseYesbycashFee($amount);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getYesbycashFee());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getBaseYesbycashFee());

        return $this;
    }

}