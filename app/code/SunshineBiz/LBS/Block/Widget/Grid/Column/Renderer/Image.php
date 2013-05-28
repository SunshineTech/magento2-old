<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_Widget_Grid_Column_Renderer_Image extends Mage_Backend_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        
        if ($url = $row->getData($this->getColumn()->getIndex())) {
            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::getBaseUrl('media') . $url;
            }
            
            return '<img src="' . $url . '" height="22" width="22" class="small-image-preview v-middle"/>';
        }

        return null;
    }
}