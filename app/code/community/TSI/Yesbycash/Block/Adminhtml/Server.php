<?php

class TSI_Yesbycash_Block_Adminhtml_Server extends Mage_Adminhtml_Block_System_Config_Form_Field {

    /**
     * Test if the module is in test mode or not
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);

        if ($this->getStoreConfig('mode_test')) {
            $html = $this->getStoreConfig('server_dev');
            $html .= '<br /><strong><span style="color:red">' . $this->__('Mode test activé') . '</span></strong>';
        } else {
            $html = $this->getStoreConfig('server_prod');
        }

        return $html;
    }

    /**
     * Retrieve config value by path
     * 
     * @param string $param
     * @return mixed
     */
    protected function getStoreConfig($param) {
        return Mage::helper('tsi_yesbycash')->getStoreConfig($param);
    }

}
