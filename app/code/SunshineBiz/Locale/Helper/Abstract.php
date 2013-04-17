<?php

/**
 * SunshineBiz_Locale Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Locale
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Locale_Helper_Abstract extends Mage_Core_Helper_Abstract {

    public function getLocaleLabel($language = null) {
        
        $locale = Mage::app()->getLocale();
        if (empty($language)) {
            $language = $locale->getLocaleCode();
        }
        $data = explode('_', $language);
        $label = ucwords($locale->getTranslation($data[0], 'language'))
                . ' (' . $locale->getTranslation($data[1], 'country') . ')';

        return $label;
    }
}