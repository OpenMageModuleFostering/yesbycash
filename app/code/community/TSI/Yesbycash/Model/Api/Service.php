<?php

class TSI_Yesbycash_Model_Api_Service {

    protected $_server;
    protected $_merchantId;
    protected $_merchantKey;
    protected $_key;
    protected $_soapClient;
    protected $_soapOptions = array();

    const TSI_YESBYCASH_SOAP_VERSION = SOAP_1_1;

    /**
     * 
     */
    public function __construct() {
        $this->_server = $this->getServer();
        $this->_merchantId = $this->getMerchantId();
        $this->_merchantKey = $this->getMerchantKey();
        $this->_key = $this->getKey();
        $this->_soapClient = $this->getClient();
    }

    /**
     * 
     * @param type $param
     * @return type
     */
    public function getStoreConfig($param) {
        return Mage::helper('tsi_yesbycash')->getStoreConfig($param);
    }

    /**
     * 
     * @return type
     */
    public function isTestMode() {
        return $this->getStoreConfig('mode_test');
    }

    /**
     * 
     * @return type
     */
    public function getServer() {
        if ($this->isTestMode()) {
            $this->_server = $this->getStoreConfig('server_dev');
        } else {
            $this->_server = $this->getStoreConfig('server_prod');
        }

        return $this->_server;
    }

    /**
     * 
     * @return type
     */
    public function getEndpoint() {
        return $this->getStoreConfig('endpoint');
    }

    /**
     * 
     * @return type
     */
    public function getMerchantId() {
        if (is_null($this->_merchantId))
            $this->_merchantId = $this->getStoreConfig('merchant_id');

        return $this->_merchantId;
    }

    /**
     * 
     * @return type
     */
    public function getMerchantKey() {
        if (is_null($this->_merchantKey))
            $this->_merchantKey = $this->getStoreConfig('merchant_key');

        return $this->_merchantKey;
    }

    /**
     * 
     * @return type
     */
    public function getKey() {
        if (is_null($this->_key))
            $this->_key = $this->getStoreConfig('key');

        return $this->_key;
    }

    /**
     * 
     * @return type
     */
    public function getClient() {
        if (is_null($this->_soapClient)) {
            $this->_soapOptions = array(
                'location' => $this->_server,
                'trace' => 0,
                'soap_version' => self::TSI_YESBYCASH_SOAP_VERSION);
            try {
                $this->_soapClient = new SoapClient($this->_server . $this->getEndpoint(), $this->_soapOptions);
            } catch (Exception $e) {
                Mage::throwException($e);
            }
        }

        return $this->_soapClient;
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    protected function hmacCalculation($params) {
        $timestamp = time();
        $transactionId = md5($timestamp);

        $str = $this->_merchantId . '#' . $transactionId . '#' . $timestamp;
        $signature = hash_hmac('sha1', $str, $this->_key);

        $params['transactionid'] = $transactionId;
        $params['timestamp'] = $timestamp;
        $params['signature'] = $signature;
        $params['merchantid'] = $this->_merchantId;
        $params['keyid'] = $this->_merchantKey;
        $params['orderTtl'] = null;

        return $params;
    }

    /**
     * 
     * @param type $service
     * @param type $args
     * @return \Varien_Object
     */
    protected function call($service, $args) {
        $result = false;
        try {
            $result = $this->_soapClient->__soapCall($service, array($args));
        } catch (SoapFault $sf) {
            if (!$result) {
                $result = new Varien_Object();
                $result->setData('error', true);
                $result->setData('fault_code', $sf->faultcode);
                $result->setData('fault_string', $sf->faultstring);
            }
        }

        return $result;
    }

}
