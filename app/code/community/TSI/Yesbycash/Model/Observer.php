<?php

class TSI_Yesbycash_Model_Observer {

    /**
     * 
     * @param Varien_Event_Observer $observer
     * @return \TSI_Yesbycash_Model_Observer
     */
    public function setRedirectUrl(Varien_Event_Observer $observer) {
        $quote = $observer->getQuote();
        $redirectUrl = $quote->getPayment()->getOrderPlaceRedirectUrl();
        Mage::getSingleton('checkout/type_onepage')->getCheckout()->setRedirectUrl($redirectUrl);

        return $this;
    }

    /**
     * 
     * @param Varien_Event_Observer $observer
     */
    public function setOutlet(Varien_Event_Observer $observer) {
        $outletId = Mage::app()->getRequest()->getPost('ybc_outlet_selecta');
        Mage::getSingleton('checkout/session')->setOutletId($outletId);
    }

    /**
     * 
     * @param Varien_Event_Observer $observer
     */
    public function checkTelephone(Varien_Event_Observer $observer) {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $payment = $quote->getPayment();
        $method = $payment->getMethod();

        $telephone = $quote->getBillingAddress()->getTelephone();

        if ($method == 'yesbycash_standard') {
            if (!preg_match('#^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$#', $telephone)) {
                Mage::getSingleton('checkout/session')->addError(Mage::helper('tsi_yesbycash')->__('Le numéro de téléphone renseigné n\'est pas correct : ' . $telephone));
                $quote->setHasError(true);
                Mage::app()->getResponse()
                        ->setHeader('HTTP/1.1', '403 Session Expired')
                        ->sendResponse();
            }
        }
    }

}
