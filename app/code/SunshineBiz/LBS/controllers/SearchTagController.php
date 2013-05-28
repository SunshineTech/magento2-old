<?php

/**
 * SunshineBiz_LBS search tag controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_SearchTagController extends Mage_Core_Controller_Front_Action {
    
    public function indexAction() {
        
        $modifiedSince = $this->getRequest()->getParam('modifiedSince');
        if ($modifiedSince == 'null') {
            $modifiedSince = "1000-01-01";
        }
        $tags = Mage::getResourceModel('SunshineBiz_LBS_Model_Resource_SearchTag_Collection')
                ->addFieldToFilter('main_table.updated_at', array('gt' => $modifiedSince))
                ->load()->toArray();
        $tags = isset($tags['items']) ? $tags['items'] : $tags;
        $this->getResponse()->setHeader("Content-Type", "text/plain");
        $this->getResponse()->setBody($this->getRequest()->getParam("jsoncallback") . "(" . Mage::helper('Mage_Core_Helper_Data')->jsonEncode($tags) . ")");
    }
}
