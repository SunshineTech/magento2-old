<?php

/**
 * SunshineBiz_Location area edit block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Area_Edit extends SunshineBiz_Location_Block_Widget_Form_Container {

    public function _construct() {

        $this->_controller = 'area';

        parent::_construct();

        $this->_updateButton('save', 'label', $this->_helper->__('Save Area'));
        $this->_updateButton('delete', 'label', $this->_helper->__('Delete Area'));
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
        if (Mage::registry('locations_area')->getId()) {
            $areaName = $this->escapeHtml(Mage::registry('locations_area')->getName());
            return $this->_helper->__("Edit Area '%s'", $areaName);
        } else {
            return $this->_helper->__('New Area');
        }
    }
}
