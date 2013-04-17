<?php

/**
 * SunshineBiz_Location building resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Building extends SunshineBiz_Locale_Model_Resource_Abstract {
    
    protected $_helper;

    protected function _construct() {
        $this->_helper = Mage::helper('SunshineBiz_Location_Helper_Data');
        $this->_init('location_building', 'id');
        $this->_nameTable = $this->getTable('location_building_name');
        $this->_fkFieldName = 'building_id';
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $building) {
        
        if (! $building->getId()) {
            $building->setCreatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());
        }
        $building->setUpdatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());

        return parent::_beforeSave($building);
    }

    protected function _initUniqueFields() {
        
        $this->_uniqueFields = array(
            array(
                'field' => array('default_name', 'area_id'),
                'title' => Mage::helper('SunshineBiz_Location_Helper_Data')->__('In the same area, the building with the same default name')
            ),
        );
        return $this;
    }

    public function isUniqueLocaleName(Mage_Core_Model_Abstract $building, $name) {
        
        $adapter = $this->_getReadAdapter();
        $joinCondition = $adapter->quoteInto('bname.building_id = building.id AND bname.locale = ?', $name['locale']);
        $select = $adapter->select()
                ->from(array('building' => $this->getMainTable()))
                ->joinLeft(array('bname' => $this->_nameTable), $joinCondition)
                ->where('building.area_id = ?', $building->getAreaId())
                ->where('bname.name = ?', $name['locale'])
                ->where('building.id <> ?', $building->getId());
        $data = $adapter->fetchRow($select);
        if ($data) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError(
                    $this->_helper->__('In the same area, the building with the same %s name already exists.', $this->_helper->getLocaleLabel($data['locale']))
            );
            return false;
        }

        return true;
    }
    
    public function getNameCols() {
        return array('name', 'mnemonic', 'address');
    }
}