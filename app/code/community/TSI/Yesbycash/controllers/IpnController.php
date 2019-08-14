<?php

class TSI_Yesbycash_IpnController extends TSI_Yesbycash_Controller_Action {

    /**
     * 
     */
    public function s2sAction() {
        $reference = (int) $this->getRequest()->getParam('reference');
        if ($reference) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($reference);
            if ($order->getId()) {
                $api = $this->getApi();
                $barcode = $order->getYesbycashBarcode();
                $status = $order->getStatus();
                $ybcOrder = $api->getOrder($barcode);
                $statusAndState = $this->_getHelper()->getStatusAndStateByCodeStatus($ybcOrder->orderstatus);
                $newStatus = $statusAndState[0];
                $newState = $statusAndState[1];
                if ($newStatus != $status) {
                    $order->setState($newState, $newStatus, '', true);
                    $order->save();
                    echo 'OK - Order ' . $reference . ' updated (' . $this->_getHelper()->getRemoteAddr() . ') - status : ' . $newStatus . ' - state :' . $newState;
                } else {
                    echo 'Order already updated';
                }
            } else {
                echo 'Order reference invalid';
            }
        } else {
            echo 'Missing reference';
        }
    }

    public function paymentupdateAction() {
        $reference = (int) $this->getRequest()->getParam('reference');
        if ($reference) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($reference);
            if ($order->getId()) {
                $status = $order->getStatus();
                if ($status != 'complete' && $status != 'processing_yesbycash_paid') {
                    if ($status == 'pending_yesbycash') {
                        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                        $invoice->register();

                        $transactionSave = Mage::getModel('core/resource_transaction')
                                ->addObject($invoice)
                                ->addObject($invoice->getOrder());

                        $transactionSave->save();
                        $order->setState('processing', 'processing_yesbycash_paid', '', true);
                        $order->save();
                        echo 'OK - Order ' . $reference . ' updated (' . $this->_getHelper()->getRemoteAddr() . ') - status : processing_yesbycash_paid - state : processing';
                    } else {
                        echo 'Operation denied';
                    }
                } else {
                    echo 'Order already paid';
                }
            } else {
                echo 'Order reference invalid';
            }
        } else {
            echo 'Missing reference';
        }
    }

    public function cancelAction() {
        $orderId = (int) $this->getRequest()->getParam('order_id');
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                $status = $order->getStatus();
                if ($status != 'complete' && $status != 'processing_yesbycash_paid') {
                    if ($status == 'pending_yesbycash') {
                        $api = $this->getApi();
                        $barcode = $order->getYesbycashBarcode();
                        $ybcOrder = $api->getOrder($barcode);
                        $statusAndState = $this->_getHelper()->getStatusAndStateByCodeStatus($ybcOrder->orderstatus);
                        $newStatus = $statusAndState[0];
                        if ($status == $newStatus) {
                            $order->setState('canceled', 'canceled', '', true);
                            $order->save();
                            $api->updateOrder($barcode, 6, 'Canceled by customer');
                            Mage::getSingleton('core/session')->addSuccess($this->__('Votre commande est annulée'));
                        } else {
                            Mage::getSingleton('core/session')->addError($this->__('Votre commande est déjà annulée'));
                        }
                    } elseif ($status == 'canceled') {
                        Mage::getSingleton('core/session')->addError($this->__('Votre commande est déjà annulée'));
                    } else {
                        Mage::getSingleton('core/session')->addError($this->__('Opération non permise'));
                    }
                } else {
                    Mage::getSingleton('core/session')->addError($this->__('Commande déjà payée'));
                }

                $this->_redirect('sales/order/view', array('order_id' => $orderId));
                return;
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Numéro de commande invalide'));
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Numéro de commande invalide ou non présent'));
        }
        $this->_redirect('sales/order/view');
        return;
    }

    /**
     * 
     * @return type
     */

    protected function getApi() {
    return Mage::getModel('tsi_yesbycash/api_methods');
    }

}
