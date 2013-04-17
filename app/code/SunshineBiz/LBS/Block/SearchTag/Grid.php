<?php

/**
 * SunshineBiz_LBS search tag grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_SearchTag_Grid extends SunshineBiz_LBS_Block_Widget_Grid {

    public function _construct() {        
        parent::_construct();
        $this->setId('lbsSearchTagGrid');
        $this->setDefaultSort('parent_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('SunshineBiz_LBS_Model_Resource_SearchTag_Collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        
        $this->addColumn('id', array(
            'header' => $this->_helper->__('ID'),
            'index' => 'id',
            'type' => 'number',
        ));
        
         $this->addColumn('img', array(
            'header' => $this->_helper->__('Image'),
            'index' => 'img',
            'filter' => null,
            'renderer' => 'SunshineBiz_LBS_Block_Widget_Grid_Column_Renderer_Image',
        ));

        $this->addColumn('default_name', array(
            'header' => $this->_helper->__('Default Name'),
            'index' => 'default_name'
        ));
        
        $this->addColumn('name', array(
            'header' => $this->_helper->__("%s Name", $this->_helper->getLocaleLabel()),
            'index' => 'name'
        ));

        $this->addColumn('priority', array(
            'header' => $this->_helper->__('Priority'),
            'index' => 'priority',
            'type' => 'number',
        ));
        
        $this->addColumn('normal_priority', array(
            'header' => $this->_helper->__('Nomal Priority'),
            'index' => 'normal_priority',
            'type' => 'number',
        ));
        
        $this->addColumn('near_priority', array(
            'header' => $this->_helper->__('Near Priority'),
            'index' => 'near_priority',
            'type' => 'number',
        ));

        $this->addColumn('parent_id', array(
            'header' => $this->_helper->__('Parent'),
            'index' => 'parent_id',
            'filter' => 'SunshineBiz_LBS_Block_Widget_Grid_Column_Filter_SearchTag',
            'renderer' => 'SunshineBiz_LBS_Block_Widget_Grid_Column_Renderer_SearchTag',
        ));
        
        $this->addColumn('is_active', array(
            'header' => $this->_helper->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                '1' => $this->_helper->__('Active'),
                '0' => $this->_helper->__('Inactive')
            ),
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdFilter('id');
        $this->getMassactionBlock()->setFormFieldName('searchTag');

        $this->getMassactionBlock()->addItem('massChangeStatus', array(
            'label' => $this->_helper->__('Mass Change Status'),
            'url' => $this->getUrl('*/*/massChangeStatus'),
            'confirm' => $this->_helper->__('Are you sure?')
        ));
        
        $this->getMassactionBlock()->addItem('massDelete', array(
            'label' => $this->_helper->__('Mass Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_helper->__('Are you sure?')
        ));

        return $this;
    }
}