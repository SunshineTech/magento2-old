<?php

/**
 * SunshineBiz_Location area resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Area extends SunshineBiz_Locale_Model_Resource_Abstract {

    protected $_helper;
    
    protected function _construct() {
        $this->_helper = Mage::helper('SunshineBiz_Location_Helper_Data');
        $this->_init('location_area', 'id');
        $this->_nameTable = $this->getTable('location_area_name');
        $this->_fkFieldName = 'area_id';
    }
    
    protected function _beforeDelete(Mage_Core_Model_Abstract $area) {
        
        $areas = $area->getCollection()->addFieldToFilter('parent_id', $area->getId());
        foreach ($areas as $child) {
            $this->delete($child);
        }
        
        return $this;
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $area) {
        
        if (! $area->getId()) {
            $area->setCreatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());
        }
        $area->setUpdatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());

        return parent::_beforeSave($area);
    }

    protected function _initUniqueFields() {
        $this->_uniqueFields = array(
            array(
                'field' => array('default_name', 'parent_id', 'region_id'),
                'title' => $this->_helper->__('In the same parent area, the area with the same default name')
            ),
        );
        return $this;
    }
    
    public function getNameCols() {
        return array('name', 'abbr');
    }
    
    public function isUniqueLocaleName(Mage_Core_Model_Abstract $area, $name) {
        
        $label = $this->_helper->getLocaleLabel($name['locale']);
        $adapter = $this->_getReadAdapter();
        $joinCondition = $adapter->quoteInto('aname.area_id = area.id AND aname.locale = ?', $name['locale']);
        $select = $adapter->select()
                ->from(array('area' => $this->getMainTable()))
                ->joinLeft(array('aname' => $this->_nameTable), $joinCondition)
                ->where('area.parent_id = ?', $area->getParentId())
                ->where('area.region_id = ?', $area->getRegionId())
                ->where('aname.name = ?', $name['name'])
                ->where('area.id <> ?', $area->getId());
        $data = $adapter->fetchRow($select);
        if ($data) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError(                    
                    $this->_helper->__('In the same parent area, the area with the same %s name already exists.', $label)
            );
            return false;
        }
        
        $parent = Mage::getModel('SunshineBiz_Location_Model_Area')->setLocale($name['locale'])->load($area->getParentId());
        if ($parent && $parent->getLocaleName() === $name['name']) {
             Mage::getSingleton('Mage_Backend_Model_Session')->addError(
                     $this->_helper->__('This %s name can\'t be the same as its parent\'s %s name.', array($label, $label))
             );
             return false;
        }

        return true;
    }
}