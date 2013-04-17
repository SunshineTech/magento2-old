<?php

/**
 * SunshineBiz_Location Region resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Region extends SunshineBiz_Locale_Model_Resource_Abstract {

    protected $_helper;
    
    protected function _construct() {
        $this->_helper = Mage::helper('SunshineBiz_Location_Helper_Data');
        $this->_init('directory_country_region', 'region_id');
        $this->_nameTable = $this->getTable('directory_country_region_name');
        $this->_fkFieldName = 'region_id';
    }
    
    protected function _initUniqueFields() {
        $this->_uniqueFields = array(
            array(
                'field' => array('code', 'country_id'),
                'title' => $this->_helper->__('In the same country, the region with the same code')
            ),
            array(
                'field' => array('default_name', 'country_id'),
                'title' => $this->_helper->__('In the same country, the region with the same default name')
            ),
        );
        return $this;
    }
    
    public function getNameCols() {
        return array('name', 'abbr');
    }

    protected function isUniqueLocaleName(Mage_Core_Model_Abstract $region, $name) {
        
        $adapter = $this->_getReadAdapter();
        $joinCondition = $adapter->quoteInto('rname.region_id = region.region_id AND rname.locale = ?', $name['locale']);
        $select = $adapter->select()
                ->from(array('region' => $this->getMainTable()))
                ->joinLeft(array('rname' => $this->_nameTable), $joinCondition)
                ->where('region.country_id = ?', $region->getCountryId())
                ->where('rname.name = ?', $name['name'])
                ->where('region.region_id <> ?', $region->getId());
        $data = $adapter->fetchRow($select);
        if ($data) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError(
                    $this->_helper->__('In the same country, the region with the same %s name already exists.', $this->_helper->getLocaleLabel($data['locale']))
            );
            return false;
        }

        return true;
    }
}