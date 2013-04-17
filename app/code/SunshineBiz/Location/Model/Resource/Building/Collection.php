<?php

/**
 * SunshineBiz_Location building collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Building_Collection extends SunshineBiz_Locale_Model_Resource_Abstract_Collection {

    /**
     * Resource initialization
     */
    protected function _construct() {
        
        $this->_init('SunshineBiz_Location_Model_Building', 'SunshineBiz_Location_Model_Resource_Building');

        $this->addOrder('area_id', Varien_Data_Collection::SORT_ORDER_DESC);
    }
}