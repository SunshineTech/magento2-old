<?php

/**
 * SunshineBiz_Location building edit block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Building_Edit extends SunshineBiz_Location_Block_Widget_Form_Container {

    public function _construct() {     

        $this->_controller = 'building';        

        parent::_construct();
        
        $this->_updateButton('save', 'label', $this->_helper->__('Save Building'));
        $this->_updateButton('delete', 'label', $this->_helper->__('Delete Building'));
        $this->_addButton('save_and_edit_button', array(
            'label' => $this->_helper->__('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => array(
                'mage-init' => array(
                    'button' => array('event' => 'saveAndContinueEdit', 'target' => '#edit_form'),
                ),
            ),
            ), 100
        );
    }

    public function getHeaderText() {
        if (Mage::registry('locations_building')->getId()) {
            $buildingName = $this->escapeHtml(Mage::registry('locations_building')->getName());
            return $this->_helper->__("Edit Building '%s'", $buildingName);
        } else {
            return $this->_helper->__('New Building');
        }
    }
}