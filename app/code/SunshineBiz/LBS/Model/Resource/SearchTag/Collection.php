<?php

/**
 * SunshineBiz_LBS search tag collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Model_Resource_SearchTag_Collection extends SunshineBiz_Locale_Model_Resource_Abstract_Collection {

    protected $_options = array();
    
    /**
     * Resource initialization
     */
    protected function _construct() {
        
        $this->_init('SunshineBiz_LBS_Model_SearchTag', 'SunshineBiz_LBS_Model_Resource_SearchTag');
        
        $this->addOrder('parent_id', Varien_Data_Collection::SORT_ORDER_ASC);
        $this->addOrder('priority', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    public function toOptionArray($emptyLabel = ' ') {
        
        $tags = array();
        $parent = null;
        foreach ($this as $tag) {
            if ($parent !== $tag->getParentId()) {
                $parent = $tag->getParentId();
            }
            
            $tags[$parent][] = array(
                'value' => $tag->getId(),
                'label' => $tag->getName()
            );
        }
        
        $this->_options = array();
        $this->sort($tags, $tags[0]);
        
        if (count($this->_options) > 0 && $emptyLabel !== false) {
            array_unshift($this->_options, array('value' => '', 'label' => $emptyLabel));
        }

        return $this->_options;
    }
    
    protected function sort($tags, $options, $level = 1) {
        
        foreach ($options as $option) {
            $prex = '|-';
            for ($i = 1; $i < $level; $i++) {
                $prex .= '--';
            }
            $this->_options[] = array(
                'value' => $option['value'],
                'label' => $prex . $option['label']
            );
            if (isset($tags[$option['value']])) {
                $this->sort($tags, $tags[$option['value']], $level + 1);
            }
        }
    }
}