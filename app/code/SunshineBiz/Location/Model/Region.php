<?php

/**
 * SunshineBiz_Location region model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Region extends SunshineBiz_Locale_Model_Abstract {

    protected $_helper;

    protected function _construct() {
        $this->_init('SunshineBiz_Location_Model_Resource_Region');
        $this->_helper = Mage::helper('SunshineBiz_Location_Helper_Data');
    }
    
    protected function _getValidationRulesBeforeSave() {
        
        $defaultNameNotEmpty = new Zend_Validate_NotEmpty();
        $defaultNameNotEmpty->setMessage(
            $this->_helper->__('Default name is required field.'),
            Zend_Validate_NotEmpty::IS_EMPTY
        );
        
        $countryNotEmpty = new Zend_Validate_NotEmpty();
        $countryNotEmpty->setMessage(
            $this->_helper->__('Country is required field.'),
            Zend_Validate_NotEmpty::IS_EMPTY
        );
        
        $validator = Mage::getModel('Magento_Validator_Composite_VarienObject');
        $validator->addRule($defaultNameNotEmpty, 'default_name')
                ->addRule($countryNotEmpty, 'country_id');
        
        return $validator;
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
    
    public function getAreas() {
        
        $collection = Mage::getResourceModel('SunshineBiz_Location_Model_Resource_Area_Collection');
        $collection->addRegionFilter($this->getId());
        $collection->load();

        return $collection;
    }
}