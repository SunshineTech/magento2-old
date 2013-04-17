<?php

/**
 * SunshineBiz_Locale abstract model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Locale
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_Locale_Model_Abstract extends Mage_Core_Model_Abstract {
      
    public function shouldDelete($data) {
        
        $con = true;
        foreach ($this->_getResource()->getNameCols() as $col) {
            $con = $con && (!array_key_exists($col, $data) || !trim($data[$col]));
        }
        
        return $con;
    }
    
    public function shouldUpdate($newName, $oldName) {
        
        $con = false;
        foreach ($this->_getResource()->getNameCols() as $col) {
            $con = $con || $newName[$col] !== $oldName[$col];
        }
        
        return $con;
    }
    
    public function getLocaleName() {
        return $this->getData('name');
    }

    public function getName() {
        $name = $this->getLocaleName();
        if(is_null($name)) {
            $name = $this->getData('default_name');
        }
        return $name;
    }
    
    public function addLocaleNames($locale) {
        
        if($locale) {
            $names = $this->getNames();
            $found = false;
            foreach ($names as $name) {
                if($name['locale'] && $name['locale'] == $locale) {
                    $found = true;
                    break;
                }
            }
                
            if (!$found) {
                $cols = $this->_getResource()->getNameCols();
                $name = array();
                $name['locale'] = $locale;
                foreach ($cols as $col) {
                    $name[$col] = '';
                }
                $names[] = $name;
                $this->setNames($names);
            }
        }
        
        return $this;
    }
}

