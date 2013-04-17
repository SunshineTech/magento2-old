<?php

/**
 * SunshineBiz_Location area grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Area_Grid extends SunshineBiz_Location_Block_Widget_Grid {

    public function _construct() {
        parent::_construct();
        $this->setId('locationsAreaGrid');
        $this->setDefaultSort('parent_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('SunshineBiz_Location_Model_Resource_Area_Collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('id', array(
            'header' => $this->_helper->__('ID'),
            'type' => 'number',
            'index' => 'id'
        ));

        $this->addColumn('default_name', array(
            'header' => $this->_helper->__('Default Name'),
            'index' => 'default_name'
        ));

        $this->addColumn('name', array(
            'header' => $this->_helper->__("%s Name", $this->_helper->getLocaleLabel()),
            'index' => 'name'
        ));
        
        $this->addColumn('default_abbr', array(
            'header' => $this->_helper->__('Default Abbr'),
            'index' => 'default_abbr'
        ));

        $this->addColumn('abbr', array(
            'header' => $this->_helper->__("%s Abbr", $this->_helper->getLocaleLabel()),
            'index' => 'abbr'
        ));        

        $this->addColumn('parent_id', array(
            'header' => $this->_helper->__('Parent'),
            'index' => 'parent_id',
            'filter' => 'SunshineBiz_Location_Block_Area_Grid_Column_Filter_Parent',
            'renderer' => 'SunshineBiz_Location_Block_Widget_Grid_Column_Renderer_Area',
        ));
        
        $this->addColumn('region_id', array(
            'header' => $this->_helper->__('Regions'),
            'index' => 'region_id',
            'filter' => 'SunshineBiz_Location_Block_Widget_Grid_Column_Filter_Region',
            'renderer' => 'SunshineBiz_Location_Block_Widget_Grid_Column_Renderer_Region',
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

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('area');

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