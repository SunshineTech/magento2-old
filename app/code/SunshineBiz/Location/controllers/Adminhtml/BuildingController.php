<?php

/**
 * SunshineBiz_Location building controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Adminhtml_BuildingController extends Mage_Backend_Controller_ActionAbstract {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('SunshineBiz_Location::system_location_buildings')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Locations'), $this->__('Locations'))
                ->_addBreadcrumb($this->__('Buildings'), $this->__('Buildings'));

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Buildings'));

        $this->_initAction()->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {

        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Buildings'));

        $buildingId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('SunshineBiz_Location_Model_Building');
        if ($buildingId) {
            $model->setLoadAll(true)->load($buildingId);
            if (!$model->getId()) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This building no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            $model->addLocaleNames($this->getRequest()->getParam('locale'));
            $area = Mage::getModel('SunshineBiz_Location_Model_Area')->load($model->getAreaId());
            $model->setRegionId($area->getRegionId());
            $model->setCountryId(Mage::getModel('SunshineBiz_Location_Model_Region')->load($area->getRegionId())->getCountryId());
        } else {
            $model->setCountryId(Mage::helper('Mage_Core_Helper_Data')->getDefaultCountry());
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Building'));
        $data = Mage::getSingleton('Mage_Backend_Model_Session')->getBuildingData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('locations_building', $model);
        if (isset($buildingId)) {
            $breadcrumb = $this->__('Edit Building');
        } else {
            $breadcrumb = $this->__('New Building');
        }

        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }

    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            
            $buildingId = $this->getRequest()->getParam('id');
            /** @var $model SunshineBiz_Location_Model_Building */
            $model = $this->_objectManager->create('SunshineBiz_Location_Model_Building')->setLoadAll(true)->load($buildingId);
            if ($buildingId && $model->isObjectNew()) {
                $this->_getSession()->addError($this->__('This building no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->_getSession()->addSuccess($this->__('The building has been saved.'));
                $this->_getSession()->setBuildingData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setBuildingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        } 
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        
        if($buildingId = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('SunshineBiz_Location_Model_Building')->setId($buildingId)->delete();
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess($this->__('The building has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $buildingId));
                return;
            }
        }
        
        Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Unable to find a building to delete.'));
        $this->_redirect('*/*/');
    }

    public function massChangeStatusAction() {

        $buildingIds = $this->getRequest()->getParam('building');
        if (!is_array($buildingIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to change status.'));
        } else {
            try {
                foreach ($buildingIds as $buildingId) {

                    $model = Mage::getModel('SunshineBiz_Location_Model_Building')->load($buildingId);
                    if ($model->getId()) {
                        $model->setIsActive(!$model->getIsActive)->save();
                    }
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d building(s) were successfully changed status.', count($buildingIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction() {

        $buildingIds = $this->getRequest()->getParam('building');
        if (!is_array($buildingIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($buildingIds as $buildingId) {
                    Mage::getModel('SunshineBiz_Location_Model_Building')->setId($buildingId)->delete();
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d building(s) were successfully deleted.', count($buildingIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('Mage_Core_Model_Authorization')->isAllowed('Sunshinebiz_Location::location_buildings');
    }
}