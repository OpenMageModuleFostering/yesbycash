<?php

$installer = $this;

$statusTable = $installer->getTable('sales/order_status');
$statusStateTable = $installer->getTable('sales/order_status_state');

$installer->getConnection()->insertArray(
        $statusTable, array(
    'status',
    'label'
        ), array(
    array('status' => 'pending_yesbycash', 'label' => 'En attente de paiement'),
    array('status' => 'pending_yesbycash_auth', 'label' => 'En attente de l\'authentification'),
    array('status' => 'canceled_yesbycash_refused', 'label' => 'Refusé'),
    array('status' => 'canceled_yesbycash_expired', 'label' => 'Expiré'),
    array('status' => 'processing_yesbycash_paid', 'label' => 'Payé')
        )
);

$installer->getConnection()->insertArray(
        $statusStateTable, array(
    'status',
    'state',
    'is_default'
        ), array(
    array(
        'status' => 'pending_yesbycash',
        'state' => 'pending_payment',
        'is_default' => 0
    ),
    array(
        'status' => 'pending_yesbycash_auth',
        'state' => 'pending',
        'is_default' => 0
    ),
    array(
        'status' => 'canceled_yesbycash_refused',
        'state' => 'canceled',
        'is_default' => 0
    ),
    array(
        'status' => 'canceled_yesbycash_expired',
        'state' => 'canceled',
        'is_default' => 0
    ),
    array(
        'status' => 'processing_yesbycash_paid',
        'state' => 'processing',
        'is_default' => 0
    )
        )
);
