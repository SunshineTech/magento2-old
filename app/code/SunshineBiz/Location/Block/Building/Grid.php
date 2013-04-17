<?php

/**
 * SunshineBiz_Location building grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Building_Grid extends SunshineBiz_Location_Block_Widget_Grid {

    public function _construct() {
        parent::_construct();
        $this->setId('locationsBuildingGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('SunshineBiz_Location_Model_Resource_Building_Collection');
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

        $this->addColumn('default_mnemonic', array(
            'header' => $this->_helper->__('Default Mnemonic'),
            'index' => 'default_mnemonic'
        ));

        $this->addColumn('area_id', array(
            'header' => $this->_helper->__('Areas'),
            'index' => 'area_id',
            'filter' => 'SunshineBiz_Location_Block_Widget_Grid_Column_Filter_Area',
            'renderer' => 'SunshineBiz_Location_Block_Widget_Grid_Column_Renderer_Area',
        ));

        $this->addColumn('default_address', array(
            'header' => $this->_helper->__('Default Address'),
            'index' => 'default_address'
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
        $this->getMassactionBlock()->setFormFieldName('building');

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