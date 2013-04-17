<?php

/**
 * SunshineBiz_LBS search tag grid column filter block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_Widget_Grid_Column_Filter_SearchTag extends Mage_Backend_Block_Widget_Grid_Column_Filter_Select {

    protected function _getOptions() {

        $options = Mage::getResourceModel('SunshineBiz_LBS_Model_Resource_SearchTag_Collection')
                ->load()
                ->toOptionArray(Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --'));
        
        return $options;
    }
}