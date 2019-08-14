<?php

class TSI_Yesbycash_Helper_Data extends Mage_Core_Helper_Data {

    /**
     * 
     * @param type $param
     * @return type
     */
    public function getStoreConfig($param) {
        return Mage::getStoreConfig('payment/yesbycash_standard/' . $param);
    }

    /**
     * 
     * @return type
     */
    public function isEnabled() {
        return $this->getStoreConfig('active');
    }

    /**
     * 
     * @return type
     */
    public function getAllowedIps() {
        return $this->getStoreConfig('allowed_ips');
    }

    /**
     * 
     * @return type
     */
    public function getAllowedCountrys() {
        return $this->getStoreConfig('specificcountry');
    }

    /**
     * 
     * @return type
     */
    public function getRemoteAddr() {
        return Mage::helper('core/http')->getRemoteAddr();
    }

    /**
     * 
     * @return boolean
     */
    public function isGoodServer() {
        $allowedIps = explode(",", $this->getAllowedIps());
        foreach ($allowedIps as $ip) {
            if ($ip === $this->getRemoteAddr())
                return true;
        }

        return false;
    }

    /**
     * 
     * @param type $amount
     * @return type
     */
    public function formatAmount($amount) {
        return intval($amount * 100);
    }

    /**
     * 
     * @return type
     */
    public function getBaseGrandTotal() {
        $order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
        return Mage::helper("core")->currency($order->getBaseGrandTotal(), true, false);
    }

    /**
     * Fee type is a percent or amount
     * 
     * @return type
     */
    public function getYesbycashFeeType() {
        $yesbycashFeeType = $this->getStoreConfig('yesbycash_fee_type');
        return $yesbycashFeeType;
    }

    /**
     * 
     * @return type
     */
    public function getYesbycashFeeValue() {
        $yesbycashFeeValue = $this->getStoreConfig('yesbycash_fee_value');
        return $yesbycashFeeValue;
    }

    /**
     * Calcul the yesbycash fee
     * 
     * @param type $quote
     * @return type
     */
    public function getYesbycashFee($quote = null) {
        if (is_null($quote)) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
        }
        $baseAmount = 0;
        $address = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();

        $tax = 0;
        if (Mage::getStoreConfig('tax/calculation/price_includes_tax') != 1) {
            $tax = $address->getTaxAmount();
        }

        $yesbycashFeeType = $this->getYesbycashFeeType();
        $baseYesbycashFeeValue = $this->getYesbycashFeeValue();

        if ($yesbycashFeeType == "yesbycash_fee_type_currency") {
            $baseAmount = $baseYesbycashFeeValue;
        } else {
            $subTotal = $address->getBaseSubtotal();
            $baseAmount = ($subTotal + $tax) * floatval($baseYesbycashFeeValue) / 100;
        }
        return $baseAmount;
    }

    /**
     * 
     * @param type $ybcStatus
     * @return array
     */
    public function getStatusAndStateByCodeStatus($ybcStatus) {
        // code YBC => status, state magento
        $statusByCode = array(
            1 => array('pending_yesbycash', 'pending_payment'), // Waiting
            2 => array('processing_yesbycash_paid', 'processing'), // Paid
            3 => array('pending_yesbycash', 'pending_payment'), // Waiting for payment
            4 => array('canceled_yesbycash_refused', 'cancelled'), // Refused
            5 => array('canceled_yesbycash_expired', 'canceled'), // Expired
            6 => array('canceled', 'canceled'), // Canceled
            7 => array('pending_yesbycash_auth', 'processing'), //Waiting for authentication 
        );
        if (isset($statusByCode[$ybcStatus])) {
            return $statusByCode[$ybcStatus];
        }
    }

    /**
     * The used norm for country is ISO3166 alpha3. 
     * For example: France country code is FRA. 
     * @param string $countryId
     * @return string
     */
    public function getCountryAlpha3($countryId) {
        $countryCodes = array(
            'FR' => 'FRA',
            'AF' => 'AFG',
            'AL' => 'ALB',
            'DZ' => 'DZA',
            'AS' => 'ASM',
            'AD' => 'AND',
            'AO' => 'AGO',
            'AI' => 'AIA',
            'AQ' => 'ATA',
            'AG' => 'ATG',
            'AR' => 'ARG',
            'AM' => 'ARM',
            'AW' => 'ABW',
            'AU' => 'AUS',
            'AT' => 'AUT',
            'AZ' => 'AZE',
            'BS' => 'BHS',
            'BH' => 'BHR',
            'BD' => 'BGD',
            'BB' => 'BRB',
            'BY' => 'BLR',
            'BE' => 'BEL',
            'BZ' => 'BLZ',
            'BJ' => 'BEN',
            'BM' => 'BMU',
            'BT' => 'BTN',
            'BO' => 'BOL',
            'BA' => 'BIH',
            'BW' => 'BWA',
            'BV' => 'BVT',
            'BR' => 'BRA',
            'IO' => 'IOT',
            'VG' => 'VGB',
            'BN' => 'BRN',
            'BG' => 'BGR',
            'BF' => 'BFA',
            'BI' => 'BDI',
            'KH' => 'KHM',
            'CM' => 'CMR',
            'CA' => 'CAN',
            'CV' => 'CPV',
            'KY' => 'CYM',
            'CF' => 'CAF',
            'TD' => 'TCD',
            'CL' => 'CHL',
            'CN' => 'CHN',
            'CX' => 'CXR',
            'CC' => 'CCK',
            'CO' => 'COL',
            'KM' => 'COM',
            'CG' => 'COG',
            'CD' => 'COD',
            'CK' => 'COK',
            'CR' => 'CRI',
            'HR' => 'HRV',
            'CU' => 'CUB',
            'CY' => 'CYP',
            'CZ' => 'CZE',
            'CI' => 'CIV',
            'DK' => 'DNK',
            'DJ' => 'DJI',
            'DM' => 'DMA',
            'DO' => 'DOM',
            'EC' => 'ECU',
            'EG' => 'EGY',
            'SV' => 'SLV',
            'GQ' => 'GNQ',
            'ER' => 'ERI',
            'EE' => 'EST',
            'ET' => 'ETH',
            'FK' => 'FLK',
            'FO' => 'FRO',
            'FJ' => 'FJI',
            'FI' => 'FIN',
            'GF' => 'GUF',
            'PF' => 'PYF',
            'TF' => 'ATF',
            'GA' => 'GAB',
            'GM' => 'GMB',
            'GE' => 'GEO',
            'DE' => 'DEU',
            'GH' => 'GHA',
            'GI' => 'GIB',
            'GR' => 'GRC',
            'GL' => 'GRL',
            'GD' => 'GRD',
            'GP' => 'GLP',
            'GU' => 'GUM',
            'GT' => 'GTM',
            'GG' => 'GGY',
            'GN' => 'GIN',
            'GW' => 'GNB',
            'GY' => 'GUY',
            'HT' => 'HTI',
            'HM' => 'HMD',
            'HN' => 'HND',
            'HK' => 'HKG',
            'HU' => 'HUN',
            'IS' => 'ISL',
            'IN' => 'IND',
            'ID' => 'IDN',
            'IR' => 'IRN',
            'IQ' => 'IRQ',
            'IE' => 'IRL',
            'IM' => 'IMN',
            'IL' => 'ISR',
            'IT' => 'ITA',
            'JM' => 'JAM',
            'JP' => 'JPN',
            'JE' => 'JEY',
            'JO' => 'JOR',
            'KZ' => 'KAZ',
            'KE' => 'KEN',
            'KI' => 'KIR',
            'KW' => 'KWT',
            'KG' => 'KGZ',
            'LA' => 'LAO',
            'LV' => 'LVA',
            'LB' => 'LBN',
            'LS' => 'LSO',
            'LR' => 'LBR',
            'LY' => 'LBY',
            'LI' => 'LIE',
            'LT' => 'LTU',
            'LU' => 'LUX',
            'MO' => 'MAC',
            'MK' => 'MKD',
            'MG' => 'MDG',
            'MW' => 'MWI',
            'MY' => 'MYS',
            'MV' => 'MDV',
            'ML' => 'MLI',
            'MT' => 'MLT',
            'MH' => 'MHL',
            'MQ' => 'MTQ',
            'MR' => 'MRT',
            'MU' => 'MUS',
            'YT' => 'MYT',
            'MX' => 'MEX',
            'FM' => 'FSM',
            'MD' => 'MDA',
            'MC' => 'MCO',
            'MN' => 'MNG',
            'ME' => 'MNE',
            'MS' => 'MSR',
            'MA' => 'MAR',
            'MZ' => 'MOZ',
            'MM' => 'MMR',
            'NA' => 'NAM',
            'NR' => 'NRU',
            'NP' => 'NPL',
            'NL' => 'NLD',
            'AN' => 'ANT',
            'NC' => 'NCL',
            'NZ' => 'NZL',
            'NI' => 'NIC',
            'NE' => 'NER',
            'NG' => 'NGA',
            'NU' => 'NIU',
            'NF' => 'NFK',
            'KP' => 'PRK',
            'MP' => 'MNP',
            'NO' => 'NOR',
            'OM' => 'OMN',
            'PK' => 'PAK',
            'PW' => 'PLW',
            'PS' => 'PSE',
            'PA' => 'PAN',
            'PG' => 'PNG',
            'PY' => 'PRY',
            'PE' => 'PER',
            'PH' => 'PHL',
            'PN' => 'PCN',
            'PL' => 'POL',
            'PT' => 'PRT',
            'PR' => 'PRI',
            'QA' => 'QAT',
            'RO' => 'ROU',
            'RU' => 'RUS',
            'RW' => 'RWA',
            'RE' => 'REU',
            'BL' => 'BLM',
            'SH' => 'SHN',
            'KN' => 'KNA',
            'LC' => 'LCA',
            'MF' => 'MAF',
            'PM' => 'SPM',
            'VC' => 'VCT',
            'WS' => 'WSM',
            'SM' => 'SMR',
            'SA' => 'SAU',
            'SN' => 'SEN',
            'RS' => 'SRB',
            'SC' => 'SYC',
            'SL' => 'SLE',
            'SG' => 'SGP',
            'SK' => 'SVK',
            'SI' => 'SVN',
            'SB' => 'SLB',
            'SO' => 'SOM',
            'ZA' => 'ZAF',
            'GS' => 'SGS',
            'KR' => 'KOR',
            'ES' => 'ESP',
            'LK' => 'LKA',
            'SD' => 'SDN',
            'SR' => 'SUR',
            'SJ' => 'SJM',
            'SZ' => 'SWZ',
            'SE' => 'SWE',
            'CH' => 'CHE',
            'SY' => 'SYR',
            'ST' => 'STP',
            'TW' => 'TWN',
            'TJ' => 'TJK',
            'TZ' => 'TZA',
            'TH' => 'THA',
            'TL' => 'TLS',
            'TG' => 'TGO',
            'TK' => 'TKL',
            'TO' => 'TON',
            'TT' => 'TTO',
            'TN' => 'TUN',
            'TR' => 'TUR',
            'TM' => 'TKM',
            'TC' => 'TCA',
            'TV' => 'TUV',
            'UM' => 'UMI',
            'VI' => 'VIR',
            'UG' => 'UGA',
            'UA' => 'UKR',
            'AE' => 'ARE',
            'GB' => 'GBR',
            'US' => 'USA',
            'UY' => 'URY',
            'UZ' => 'UZB',
            'VU' => 'VUT',
            'VA' => 'VAT',
            'VE' => 'VEN',
            'VN' => 'VNM',
            'WF' => 'WLF',
            'EH' => 'ESH',
            'YE' => 'YEM',
            'ZM' => 'ZMB',
            'ZW' => 'ZWE',
            'AX' => 'ALA');

        if (isset($countryCodes[$countryId])) {
            return $countryCodes[$countryId];
        } else {
            return 'FRA';
        }
    }

}
