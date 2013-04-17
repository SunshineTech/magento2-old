<?php

/**
 * SunshineBiz_LBS search tag edit block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_SearchTag_Edit extends SunshineBiz_LBS_Block_Widget_Form_Container {

    public function _construct() {
        
        $this->_controller = 'searchTag';

        parent::_construct();

        $this->_updateButton('save', 'label', $this->_helper->__('Save SearchTag'));
        $this->_updateButton('delete', 'label', $this->_helper->__('Delete SearchTag'));
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
        if (Mage::registry('lbs_searchTag')->getId()) {
            $tagName = $this->escapeHtml(Mage::registry('lbs_searchTag')->getName());
            return $this->_helper->__("Edit SearchTag '%s'", $tagName);
        } else {
            return $this->_helper->__('New SearchTag');
        }
    }
}