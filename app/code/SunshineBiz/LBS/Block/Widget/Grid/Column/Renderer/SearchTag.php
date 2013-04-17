<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_Widget_Grid_Column_Renderer_SearchTag extends Mage_Backend_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        if ($data = $row->getData($this->getColumn()->getIndex())) {
            $name = Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->load($data)->getName();
            if (empty($name)) {
                $name = $this->escapeHtml($data);
            }
            return $name;
        }

        return null;
    }

}