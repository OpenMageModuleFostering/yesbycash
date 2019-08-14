<?php

class TSI_Yesbycash_AjaxController extends TSI_Yesbycash_Controller_Action {

    /**
     * 
     */
    public function outletsAction() {
        $block = $this->getLayout()->createBlock('tsi_yesbycash/form_ajax');
        $block->setTemplate('yesbycash/payment/form_ajax.phtml');
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($block->toHtml());
    }

}
