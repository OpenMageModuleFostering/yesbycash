<?php

class TSI_Yesbycash_Controller_Action extends Mage_Core_Controller_Front_Action {

    protected $_quote = false;

    /**
     * Test for a method s2s and paymentupdate if the server is authorized
     * to execute the next step
     * 
     * @return type
     */
    public function preDispatch() {
        if ($this->_getHelper()->isEnabled()) {
            $action = $this->getRequest()->getActionName();
            $pattern = '/^(s2s)|(paymentupdate)/i';
            if (preg_match($pattern, $action)) {
                if (!$this->_validateServer()) {
                    $this->getResponse()->setBody("KO - Unauthorized ip : " . $this->_getHelper()->getRemoteAddr());
                    $this->setFlag('', 'no-dispatch', true);
                }
            }
        } else {
            $this->getResponse()->setBody("KO - Module not enabled");
            $this->setFlag('', 'no-dispatch', true);
        }

        return parent::preDispatch();
    }

    /**
     * 
     * @return boolean
     */
    protected function _validateServer() {
        if (!$this->_getHelper()->isGoodServer()) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @return type
     */
    protected function _getHelper() {
        return Mage::helper('tsi_yesbycash');
    }

    /**
     * Get the session and api yesbycash
     * Set the response to database
     * Set state
     * Send email
     * Redirect to success or failure page
     */
    public function redirectAction() {
        $session = $this->_getCheckoutSession();
        $session->setYesbycashStandardQuoteId($session->getQuoteId());
        $session->unsQuoteId();

        $api = Mage::getModel('tsi_yesbycash/api_methods');

        try {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if (!$order->getId())
                Mage::throwException("Order not found!");

            $billingAddress = $order->getBillingAddress();

            $mobile = null;
            if (preg_match('#^(06|07)#', $billingAddress->getTelephone()))
                $mobile = $billingAddress->getTelephone();

            $outletId = $session->getOutletId();

            $response = $api->createOrder($order->getCustomerId(), $this->_getHelper()->formatAmount($order->getBaseGrandTotal()), $order->getBaseCurrencyCode(), $order->getIncrementId(), $billingAddress->getEmail(), $billingAddress->getFirstname(), $billingAddress->getLastname(), $billingAddress->getTelephone(), $mobile, $billingAddress->getStreetFull(), $billingAddress->getCity(), $billingAddress->getCountryId(), $billingAddress->getPostcode(), $outletId);

            $order->setYesbycashBarcode($response->barcode);
            $order->setYesbycashStatus($response->status);
            $order->setYesbycashMessage($response->message);
            $order->setYesbycashErrorcode($response->errorcode);
            $order->setYesbycashOutletsid($outletId);
            $order->setState('pending_payment', 'pending_yesbycash', '', false);
            $order->save();

            $session->setQuoteId($session->getYesbycashStandardQuoteId(true));
            $this->_getQuote()->setIsActive(false)->save();
            $this->_getCheckoutSession()->unsQuoteId();

            $order->sendNewOrderEmail();

            $this->_redirect('checkout/onepage/success', array('_secure' => true));
        } catch (Exception $e) {
            Mage::logException($e);
            $session->setErrorMessage($e->getMessage());
            $this->_redirect('checkout/onepage/failure', array('_secure' => true));
        }
    }

    /**
     * 
     * @return type
     */
    protected function _getCheckoutSession() {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * 
     * @return type
     */
    protected function _getQuote() {
        if (!$this->_quote) {
            $this->_quote = $this->_getCheckoutSession()->getQuote();
        }
        return $this->_quote;
    }

}
