<?php

/**
 * SunshineBiz_Locale abstract resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Locale
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_Locale_Model_Resource_Abstract extends Mage_Core_Model_Resource_Db_Abstract {
    
    protected $_nameTable;
    //语言环境表的外键域名
    protected $_fkFieldName;
    /**Is it need?
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        
        $condition = array(
            "{$this->_fkFieldName} = ?"     => (int) $object->getId(),
        );

        $this->_getWriteAdapter()->delete($this->getTable($this->_nameTable), $condition);

        return parent::_beforeDelete($object);
    }
    **/
    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        
        $newNames = (array) $object->getNames();
        $oldNames = (array) $this->lookupNames($object->getId());
        if($newNames || $oldNames) {
            $oldLocales = array();
            foreach ($oldNames as $name) {
                $oldLocales[] = $name['locale'];
            }

            $newLocales = array();
            foreach ($newNames as $name) {
                $newLocales[] = $name['locale'];
            }

            $updateLocales = array_intersect($newLocales, $oldLocales);
            $insertLocales = array_diff($newLocales, $oldLocales);
            $deleteLocales = array_diff($oldLocales, $newLocales);

            $update = array();
            foreach ($updateLocales as $locale) {
                foreach ($oldNames as $oldName) {
                    if($locale == $oldName['locale'])
                        break;
                }

                foreach ($newNames as $newName) {
                    if($locale == $newName['locale'])
                        break;
                }

                if ($object->shouldDelete($newName)) {
                    $deleteLocales[] = $locale;
                    continue;
                }

                if ($object->shouldUpdate($newName, $oldName) && $this->isUniqueLocaleName($object, $newName)) {
                    $update[] = $newName;
                }
            }

            $insert = array();
            foreach ($insertLocales as $locale) {
                foreach ($newNames as $newName) {
                    if($locale == $newName['locale'])
                        break;
                }

                if ($object->shouldDelete($newName)) {
                    continue;
                }

                if ($this->isUniqueLocaleName($object, $newName)) {
                    $insert[] = $newName;
                }
            }

            $table = $this->getTable($this->_nameTable);

            if ($insert) {
                $data = array();
                
                foreach ($insert as $name) {
                    $data[] = array_merge(array("$this->_fkFieldName"  => (int) $object->getId()), $name);                    
                }
                $this->_getWriteAdapter()->insertMultiple($table, $data);
            }

            if ($deleteLocales) {
                $where = array(
                    "{$this->_fkFieldName} = ?" => (int) $object->getId(),
                    'locale IN (?)'             => $deleteLocales
                );
                $this->_getWriteAdapter()->delete($table, $where);
            }

            if($update) {
                foreach ($update as $name) {
                    $where = array(
                        "{$this->_fkFieldName} = ?" => $object->getId(),
                        'locale = ?'                => $name['locale']
                    );

                    $this->_getWriteAdapter()->update($table, $name, $where);
                }
            }
        }
        
        return parent::_afterSave($object);
    }
    
    public function lookupNames($id) {
        $cols = $this->getNameCols();
        $cols[] = 'locale';
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable($this->_nameTable), $cols)
            ->where("{$this->_fkFieldName} = :fk_id");

        $binds = array(
            ':fk_id' => (int) $id
        );

        return $adapter->fetchAll($select, $binds);
    }
    
    protected function isUniqueLocaleName(Mage_Core_Model_Abstract $object, $data) {
        return true;
    }
    
    public function getNameTable() {
        
        if (empty($this->_nameTable)) {
            Mage::throwException(Mage::helper('Mage_Core_Helper_Data')->__('Empty name table name'));
        }
        
        return $this->getTable($this->_nameTable);
    }
    
    public function getFkFieldName() {
        
        if (empty($this->_fkFieldName)) {
            Mage::throwException(Mage::helper('Mage_Core_Helper_Data')->__('Empty foreign key field name'));
        }
        
        return $this->_fkFieldName;
    }
    
    public function load(Mage_Core_Model_Abstract $object, $value, $field = null) {
        
        if($object->getLoadAll()) {
            parent::load($object, $value, $field);
            if ($object->getId()) {
                $object->setData('names', $this->lookupNames($object->getId()));
            }
        } else {
            parent::load($object, $value, $field);
        }
        
        return $this;
    }
    
    protected function _getLoadSelect($field, $value, $object) {
        
        $cols = $this->getNameCols();
        $select = parent::_getLoadSelect($field, $value, $object);
        
        $locale = $object->getLocale();
        if ($object->getLoadAll() || $locale) {
            $cols[] = 'locale';            
        } else {
            $locale = Mage::app()->getLocale()->getLocaleCode();
        }
        
        if($locale) {
            $adapter = $this->_getReadAdapter();
            $idField = $adapter->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());
            $condition = $adapter->quoteInto('lrn.locale = ?', $locale);
            $select->joinLeft(array('lrn' => $this->_nameTable), "{$idField} = lrn.{$this->_fkFieldName} AND {$condition}", $cols);
        }
        
        return $select;
    }
    
    public function getNameCols() {
        return array('name');
    }
}

