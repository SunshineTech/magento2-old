<?php

/**
 * SunshineBiz_Location area collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Area_Collection extends SunshineBiz_Locale_Model_Resource_Abstract_Collection {

    protected $_options = array();

    /**
     * Resource initialization
     */
    protected function _construct() {
        
        $this->_init('SunshineBiz_Location_Model_Area', 'SunshineBiz_Location_Model_Resource_Area');

        $this->addOrder('region_id', Varien_Data_Collection::SORT_ORDER_DESC);
        $this->addOrder('parent_id', Varien_Data_Collection::SORT_ORDER_ASC);
        $this->addOrder('id', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    public function addRegionFilter($regionId) {

        if (!empty($regionId)) {
            if (is_array($regionId)) {
                $this->addFieldToFilter('main_table.region_id', array('in' => $regionId));
            } else {
                $this->addFieldToFilter('main_table.region_id', $regionId);
            }
        }

        return $this;
    }

    public function toOptionArray($emptyLabel) {

        $areas = array();
        $region = null;
        $parent = null;
        foreach ($this as $area) {

            if ($region !== $area->getRegionId()) {
                $region = $area->getRegionId();
                $parent = $area->getParentId();
            }

            if ($parent !== $area->getParentId()) {
                $parent = $area->getParentId();
            }

            $areas[$region][$parent][] = array(
                'value' => $area->getId(),
                'label' => $area->getName()
            );
        }

        $this->_options = array();
        foreach ($areas as $area) {
            foreach ($area as $options) {
                $this->sort($area, $options);
                break;
            }
        }

        if (count($this->_options) > 0 && $emptyLabel !== false) {
            if(!$emptyLabel)
                $emptyLabel = Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --');
            array_unshift($this->_options, array('value' => '', 'label' => $emptyLabel));
        }

        return $this->_options;
    }

    protected function sort($areas, $options, $level = 1) {
        foreach ($options as $option) {
            $prex = '|-';
            for ($i = 1; $i < $level; $i++) {
                $prex .= '--';
            }
            $this->_options[] = array(
                'value' => $option['value'],
                'label' => $prex . $option['label']
            );
            if (isset($areas[$option['value']])) {
                $this->sort($areas, $areas[$option['value']], $level + 1);
            }
        }
    }

}