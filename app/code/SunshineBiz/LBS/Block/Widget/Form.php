<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_Widget_Form extends Mage_Backend_Block_Widget_Form {
    
    protected $_helper;
    
    public function _construct() {
        parent::_construct();
        $this->_helper = $this->helper('SunshineBiz_LBS_Helper_Data');
    }
}