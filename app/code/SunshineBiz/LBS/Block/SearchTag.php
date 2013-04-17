<?php

/**
 * SunshineBiz_LBS search tag block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_SearchTag extends SunshineBiz_LBS_Block_Widget_Grid_Container {

    public function _construct() {
        
        $this->_controller = 'searchTag';
        $this->_headerText = Mage::helper('SunshineBiz_LBS_Helper_Data')->__('SearchTags');
        $this->_addButtonLabel = Mage::helper('SunshineBiz_LBS_Helper_Data')->__('Add New SearchTag');
        parent::_construct();
    }
}