<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Locale_Block_Data_Form extends Varien_Data_Form {
    
    public function setValues($values) {
        
        foreach ($this->_allElements as $element) {
            $elementId = $orignId = $element->getId();
            if(strlen($elementId) > 10 && substr($elementId, 0, 5) == 'names') {
                $elementId = 'names';
            }
            if (isset($values[$elementId])) {
                if($elementId == 'names') {
                    $pos = strpos($orignId, ']');
                    $idx = intval(substr($orignId, 6, $pos - 6));
                    $name = substr($orignId, $pos + 2, -1);
                    $element->setValue($values[$elementId][$idx][$name]);
                } else {
                    $element->setValue($values[$elementId]);
                }                
            }
            else {
                $element->setValue(null);
            }
        }
        
        return $this;
    }
}
