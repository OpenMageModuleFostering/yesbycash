<?php

class TSI_Yesbycash_Model_Api_Methods extends TSI_Yesbycash_Model_Api_Service {

    /**
     * get the outlet list with api yesbycash
     * 
     * @param type $zipCode
     * @param type $countryId
     * @return type
     */
    public function getOutletsList($zipCode, $countryId) {
        $params = array(
            'zipcode' => $zipCode,
            'country' => $countryId,
            'merchantid' => $this->_merchantId,
            'keyid' => $this->_merchantKey);

        $args = $this->hmacCalculation($params);

        return $this->call('getOutletsList', $args);
    }

    /**
     * Create an order with all necessary param
     * 
     * @param type $customerId
     * @param type $amount
     * @param type $currency
     * @param type $reference
     * @param type $buyerEmail
     * @param type $buyerFName
     * @param type $buyerLName
     * @param type $buyerPhone
     * @param type $buyerMobile
     * @param type $buyerAddress
     * @param type $buyerCity
     * @param type $buyerCountry
     * @param type $buyerZip
     * @param type $selectedOutlet
     * @return type
     */
    public function createOrder($customerId, $amount, $currency, $reference, $buyerEmail, $buyerFName, $buyerLName, $buyerPhone, $buyerMobile, $buyerAddress, $buyerCity, $buyerCountry, $buyerZip, $selectedOutlet) {
        $params = array(
            'customerid' => $customerId,
            'amount' => $amount,
            'currency' => $currency,
            'reference' => $reference,
            'buyeremail' => $buyerEmail,
            'buyerfname' => $buyerFName,
            'buyerlname' => $buyerLName,
            'buyerphone' => $buyerPhone,
            'buyermobile' => $buyerMobile,
            'buyeraddress' => $buyerAddress,
            'buyercity' => $buyerCity,
            'buyercountry' => $buyerCountry,
            'buyerzip' => $buyerZip,
            'selectedoutlet' => $selectedOutlet);

        $params['urlok'] = Mage::getUrl('checkout/onepage/success', array('_secure' => true));
        //$params['urlnok'] = Mage::getUrl('checkout/onepage/failure', array('_secure' => true));
        $params['urlnok'] = Mage::getUrl('tsi_yesbycash/ipn/s2s/reference/' . $reference);
        $params['urls2s'] = Mage::getUrl('tsi_yesbycash/ipn/s2s/reference/' . $reference);
        $params['urlpaymentupdate'] = Mage::getUrl('tsi_yesbycash/ipn/paymentupdate/reference/' . $reference);

        $params['buyerip'] = Mage::helper('tsi_yesbycash')->getRemoteAddr();

        $args = $this->hmacCalculation($params);

        return $this->call('createOrder', $args);
    }

    /**
     * Update the state to the order distant (yesbycash api)
     * 
     * @param type $barCode
     * @param type $status
     * @param type $reason
     * @return type
     */
    public function updateOrder($barCode, $status, $reason) {
        $params = array(
            'barcode' => $barCode,
            'status' => $status,
            'reason' => $reason);

        $args = $this->hmacCalculation($params);

        return $this->call('updateOrder', $args);
    }

    /**
     * Get the distant order yesbycash
     * 
     * @param type $barCode
     * @return type
     */
    public function getOrder($barCode) {
        $params = array('barcode' => $barCode);

        $args = $this->hmacCalculation($params);

        return $this->call('getOrder', $args);
    }

}
