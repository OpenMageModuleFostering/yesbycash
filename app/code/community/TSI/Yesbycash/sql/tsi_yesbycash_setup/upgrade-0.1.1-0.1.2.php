<?php

$installer = new Mage_Sales_Model_Resource_Setup();

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');
$write->beginTransaction();

try {
    $installer->addAttribute('quote_address', 'yesbycash_fee', array('type' => 'decimal'));
    $installer->addAttribute('quote_address', 'base_yesbycash_fee', array('type' => 'decimal'));

    $installer->addAttribute('order', 'yesbycash_fee', array('type' => 'decimal'));
    $installer->addAttribute('order', 'base_yesbycash_fee', array('type' => 'decimal'));

    $installer->addAttribute('invoice', 'yesbycash_fee', array('type' => 'decimal'));
    $installer->addAttribute('invoice', 'base_yesbycash_fee', array('type' => 'decimal'));

    $installer->addAttribute('creditmemo', 'yesbycash_fee', array('type' => 'decimal'));
    $installer->addAttribute('creditmemo', 'base_yesbycash_fee', array('type' => 'decimal'));

    $write->commit();
} catch (Exception $e) {
    $write->rollback();
}
$installer->endSetup();
