<?php

/**
 * SunshineBiz_Location area model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Area extends SunshineBiz_Locale_Model_Abstract {

    protected $_helper;

    protected function _construct() {
        $this->_init('SunshineBiz_Location_Model_Resource_Area');
        $this->_helper = Mage::helper('SunshineBiz_Location_Helper_Data');
    }
    
    protected function _beforeSave() {
        
        if($this->getId() && $this->getId() === $this->getParentId())
            Mage::throwException($this->_helper->__('Parent can\'t be itself.'));
        
        if (!Zend_Validate::is($this->getDefaultName(), 'NotEmpty'))
                Mage::throwException($this->_helper->__('Default name is required field.'));
        
        $parent = Mage::getModel('SunshineBiz_Location_Model_Area')->load($this->getParentId());
        if ($parent && $parent->getDefaultName() === $this->getDefaultName())
                Mage::throwException($this->_helper->__('This default name can\'t be the same as its parent\'s default name.'));
            
        if (!Zend_Validate::is($this->getRegionId(), 'NotEmpty'))
                Mage::throwException($this->_helper->__('Region is required field.'));
        
        return parent::_beforeSave();
    }
    
    public function getLocaleAbbr() {
        return $this->getData('abbr');
    }

    public function getAbbr() {
        $name = $this->getLocaleAbbr();
        if(is_null($name)) {
            $name = $this->getData('default_abbr');
        }
        return $name;
    }
}