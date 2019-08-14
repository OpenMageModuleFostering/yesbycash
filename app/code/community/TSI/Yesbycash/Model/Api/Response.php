<?php

class TSI_Yesbycash_Model_Api_Response extends Varien_Object {

    /**
     * Not used
     * 
     * @var type 
     */
    protected $_responseCode = array(
        00000 => 'OK Method was succesful',
        00001 => 'Authentication required',
        01001 => 'Missing signature',
        01002 => 'Invalid signature',
        01003 => 'Missing merchant identifier',
        01004 => 'Invalid merchant identifier',
        01005 => 'Missing parameter customerid',
        01006 => 'Invalid parameter customerid',
        01007 => 'Missing transaction identifier',
        01008 => 'Invalid transaction identifier',
        01009 => 'Missing amount',
        01010 => 'Invalid amount',
        01011 => 'Missing currency',
        01012 => 'Invalid currency',
        01013 => 'Missing key identifier (keyid)',
        01014 => 'Invalid key identifier (keyid)',
        01015 => 'Missing timestamp parameter',
        01016 => 'Missing timestamp parameter',
        01017 => 'Missing expirytime parameter',
        01025 => 'Missing buyeremail parameter',
        01026 => 'Invalid buyeremail parameter',
        01027 => 'Missing buyeraddress parameter',
        01028 => 'Invalid buyeraddress parameter',
        01029 => 'Missing buyercity parameter',
        01030 => 'Invalid buyercity parameter',
        01031 => 'Missing buyerzip parameter',
        01032 => 'Invalid buyerzip parameter',
        01037 => 'Missing URL OK',
        01038 => 'Invalid URL OK parameter',
        01039 => 'Missing URL NOK',
        01040 => 'Invalid URL NOK parameter',
        01041 => 'Missing URL S2S',
        01042 => 'Invalid URL S2S parameter',
        01043 => 'Missing lname parameter',
        01044 => 'Invalid lname parameter',
        01045 => 'Missing fname parameter',
        01046 => 'Invalid fname parameter',
        01047 => 'Missing customerip parameter',
        01048 => 'Invalid customerip parameter',
        01049 => 'Missing buyercountry parameter',
        01050 => 'Invalid buyercountry parameter',
        01051 => 'Missing buyerjob parameter',
        01052 => 'Invalid buyerjob parameter',
        01053 => 'Missing buyerbirthdate parameter',
        01054 => 'Invalid buyerbirthdate parameter',
        01055 => 'Missing buyerbirthcity parameter',
        08000 => 'Unreachable Error',
        01055 => 'Missing buyerbirthcity parameter',
        01057 => 'Invalid HTTP USER AGENT',
        01058 => 'Invalid HTTP ACCEPT',
        01075 => 'Missing zipcode parameter',
        01082 => 'Invalid barcode parameter',
        01081 => 'Missing barcode parameter',
        01076 => 'Invalid zipcode parameter',
        01079 => 'Missing country parameter',
        01080 => 'Invalid country parameter',
        02002 => 'Incorrect merchant identifier',
        02003 => 'Key identifer not found',
        02004 => 'Incorrect signature',
        02009 => 'Currency not available for this merchant',
        02011 => 'User blacklisted',
        02014 => 'Canceled: Max number of transactions/card reached',
        02015 => 'Canceled: Max total amount of transactions/card reached',
        02016 => 'Canceled: Max number of transactions/email reached',
        02017 => 'Canceled: Max total amount of transactions/email reached',
        02018 => 'Canceled: Max number of transactions/customer reached',
        02019 => 'Canceled: Max total amount of transactions/customer reached',
        02020 => 'No transaction found',
        02028 => 'No outlet found',
        02030 => 'Max total amount of transactions/customer reached',
        02031 => 'Authentication not confirmed',
        02101 => 'Technical Error');

    /**
     * 
     * @return type
     */
    public function isSuccess() {
        return $this->getErrorcode() == 0000;
    }

    /**
     * 
     * @return type
     */
    public function getErrorcode() {
        return $this->getData('errorcode');
    }

    /**
     * 
     * @return type
     */
    public function getErrormessage() {
        $errormessage = 'Error';
        $errorcode = (int) $this->getErrorcode();
        if (isset($this->_responseCode[$errorcode]))
            $errormessage = $this->_responseCode[$errorcode];

        return Mage::helper('tsi_yesbycash')->__($errormessage);
    }

}
