<?php

/**
 * SunshineBiz_Location Country collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Country_Collection extends Mage_Directory_Model_Resource_Country_Collection {
    
    public function toOptionArray($emptyLabel) {
        if($emptyLabel !== false && !$emptyLabel)
                $emptyLabel = Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --');
        
        return parent::toOptionArray($emptyLabel);
    }
}