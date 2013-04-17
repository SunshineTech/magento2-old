<?php

/**
 * SunshineBiz_Location area controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Adminhtml_AreaController extends Mage_Backend_Controller_ActionAbstract {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('SunshineBiz_Location::system_location_areas')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Locations'), $this->__('Locations'))
                ->_addBreadcrumb($this->__('Areas'), $this->__('Areas'));

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Areas'));

        $this->_initAction()->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {

        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Areas'));

        $areaId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('SunshineBiz_Location_Model_Area');
        if ($areaId) {
            $model->setLoadAll(true)->load($areaId);
            if (!$model->getId()) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This area no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            $model->addLocaleNames($this->getRequest()->getParam('locale'));
            $model->setCountryId(Mage::getModel('SunshineBiz_Location_Model_Region')->load($model->getRegionId())->getCountryId());
        } else {
            $model->setCountryId(Mage::helper('Mage_Core_Helper_Data')->getDefaultCountry());
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Area'));
        $data = Mage::getSingleton('Mage_Backend_Model_Session')->getAreaData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('locations_area', $model);
        if ($areaId) {
            $breadcrumb = $this->__('Edit Area');
        } else {
            $breadcrumb = $this->__('New Area');
        }

        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }

    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            
            $areaId = $this->getRequest()->getParam('id');
            /** @var $model SunshineBiz_Location_Model_Area */
            $model = $this->_objectManager->create('SunshineBiz_Location_Model_Area')->setLoadAll(true)->load($areaId);
            if ($areaId && $model->isObjectNew()) {
                $this->_getSession()->addError($this->__('This area no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->_getSession()->addSuccess($this->__('The area has been saved.'));
                $this->_getSession()->setAreaData(false);

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
                $this->_getSession()->setAreaData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        } 
        $this->_redirect('*/*/');
    }

    public function deleteAction() {

        if($areaId = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('SunshineBiz_Location_Model_Area')->setId($areaId)->delete();
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess($this->__('The area has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $areaId));
                return;
            }
        }
        
        Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Unable to find an area to delete.'));
        $this->_redirect('*/*/');
    }

    public function massChangeStatusAction() {

        $areaIds = $this->getRequest()->getParam('area');
        if (!is_array($areaIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to change status.'));
        } else {
            try {
                foreach ($areaIds as $areaId) {

                    $model = Mage::getModel('SunshineBiz_Location_Model_Area')->load($areaId);
                    if ($model->getId()) {
                        $model->setIsActive(!$model->getIsActive())->save();
                    }
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d area(s) were successfully changed status.', count($areaIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction() {

        $areaIds = $this->getRequest()->getParam('area');
        if (!is_array($areaIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($areaIds as $areaId) {
                    Mage::getModel('SunshineBiz_Location_Model_Area')->setId($areaId)->delete();
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d area(s) were successfully deleted.', count($areaIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('Mage_Core_Model_Authorization')->isAllowed('Sunshinebiz_Location::location_areas');
    }
}