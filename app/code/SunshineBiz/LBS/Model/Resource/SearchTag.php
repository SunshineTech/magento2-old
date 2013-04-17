<?php

/**
 * SunshineBiz_LBS search tag resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Model_Resource_SearchTag extends SunshineBiz_Locale_Model_Resource_Abstract {

    protected $_helper;
    
    protected function _construct() {
        $this->_helper = Mage::helper('SunshineBiz_LBS_Helper_Data');
        $this->_init('lbs_search_tag', 'id');
        $this->_nameTable = $this->getTable('lbs_search_tag_name');
        $this->_fkFieldName = 'tag_id';
    }
    
    protected function _beforeDelete(Mage_Core_Model_Abstract $tag) {
        
        $tags = $tag->getCollection()->addFieldToFilter('parent_id', $tag->getId());
        foreach ($tags as $child) {
            $this->delete($child);
        }
        
        return $this;
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $tag) {
        
        if (! $tag->getId()) {
            $tag->setCreatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());
        }
        $tag->setUpdatedAt(Mage::getSingleton('Mage_Core_Model_Date')->gmtDate());

        return parent::_beforeSave($tag);
    }

    protected function _initUniqueFields() {
        
        $this->_uniqueFields = array(
            array(
                'field' => array('default_name', 'parent_id'),
                'title' => $this->_helper->__('In the same level, the tag with the same default name')
            ),
        );
        return $this;
    }
    
    public function isUniqueLocaleName(Mage_Core_Model_Abstract $tag, $name) {
        
        $label = $this->_helper->getLocaleLabel($name['locale']);
        $adapter = $this->_getReadAdapter();
        $joinCondition = $adapter->quoteInto('tname.tag_id = tag.id AND tname.locale = ?', $name['locale']);
        $select = $adapter->select()
                ->from(array('tag' => $this->getMainTable()))
                ->joinLeft(array('tname' => $this->_nameTable), $joinCondition)
                ->where('tag.parent_id = ?', $tag->getParentId())
                ->where('tname.name = ?', $name['name'])
                ->where('tag.id <> ?', $tag->getId());
        $data = $adapter->fetchRow($select);
        if ($data) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError(                    
                    $this->_helper->__('In the same parent search tag, the search tag with the same %s name already exists.', $label)
            );
            return false;
        }
        
        $parent = Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->setLocale($name['locale'])->load($tag->getParentId());
        if ($parent && $parent->getLocaleName() === $name['name']) {
             Mage::getSingleton('Mage_Backend_Model_Session')->addError(
                     $this->_helper->__('This %s name can\'t be the same as its parent\'s %s name.', array($label, $label))
             );
             return false;
        }

        return true;
    }
}