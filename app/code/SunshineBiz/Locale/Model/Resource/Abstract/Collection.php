<?php

/**
 * SunshineBiz_Locale abstract collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Locale
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_Locale_Model_Resource_Abstract_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    
    protected function _initSelect() {
        
        parent::_initSelect();
        $locale = Mage::app()->getLocale()->getLocaleCode();

        $this->addBindParam(':locale', $locale);
        $this->getSelect()->joinLeft(
            array('name_table' => $this->getResource()->getNameTable()),
            "main_table.{$this->getResource()->getIdFieldName()} = name_table.{$this->getResource()->getFkFieldName()} AND name_table.locale = :locale",
            $this->getResource()->getNameCols());

        return $this;
    }
}
