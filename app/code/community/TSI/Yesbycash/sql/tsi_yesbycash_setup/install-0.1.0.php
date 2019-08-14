<?php

$installer = $this;
$installer->startSetup();

$this->removeAttribute('order', 'yesbycash_barcode');
$this->removeAttribute('order', 'yesbycash_status');
$this->removeAttribute('order', 'yesbycash_message');
$this->removeAttribute('order', 'yesbycash_errorcode');
$this->removeAttribute('order', 'yesbycash_outletsid');

$this->addAttribute('order', 'yesbycash_barcode', array(
    'type' => 'varchar',
    'label' => 'Yesbycash Barcode',
    'visible' => true,
    'required' => false,
    'input' => 'text',
));

$this->addAttribute('order', 'yesbycash_status', array(
    'type' => 'varchar',
    'label' => 'Yesbycash Status',
    'visible' => true,
    'required' => false,
    'input' => 'text',
));

$this->addAttribute('order', 'yesbycash_message', array(
    'type' => 'varchar',
    'label' => 'Yesbycash Message',
    'visible' => true,
    'required' => false,
    'input' => 'text',
));

$this->addAttribute('order', 'yesbycash_errorcode', array(
    'type' => 'varchar',
    'label' => 'Yesbycash Errorcode',
    'visible' => true,
    'required' => false,
    'input' => 'text',
));

$this->addAttribute('order', 'yesbycash_outletsid', array(
    'type' => 'varchar',
    'label' => 'Yesbycash Outlet id',
    'visible' => true,
    'required' => false,
    'input' => 'text',
));
