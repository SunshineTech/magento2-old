<?php

/**
 * SunshineBiz_LBS search tag model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Model_SearchTag extends SunshineBiz_Locale_Model_Abstract {
    
    protected $_helper;
    
    protected function _construct() {
        $this->_init('SunshineBiz_LBS_Model_Resource_SearchTag');
        $this->_helper = Mage::helper('SunshineBiz_LBS_Helper_Data');
    }
    
    protected function _beforeSave() {
        
        if($this->getId() && $this->getId() === $this->getParentId())
            Mage::throwException($this->_helper->__('Parent can\'t be itself.'));
        
        if (!Zend_Validate::is($this->getDefaultName(), 'NotEmpty'))
                Mage::throwException($this->_helper->__('Default name is required field.'));
        
        $parent = Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->load($this->getParentId());
        if ($parent && $parent->getDefaultName() === $this->getDefaultName())
                Mage::throwException($this->_helper->__('This default name can\'t be the same as its parent\'s default name.'));
        
        return parent::_beforeSave();
    }
}