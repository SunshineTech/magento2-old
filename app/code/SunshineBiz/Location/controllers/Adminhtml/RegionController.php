<?php

/**
 * SunshineBiz_Location Region controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Adminhtml_RegionController extends Mage_Backend_Controller_ActionAbstract {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('SunshineBiz_Location::system_location_regions')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Locations'), $this->__('Locations'))
                ->_addBreadcrumb($this->__('Regions'), $this->__('Regions'));

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Regions'));

        $this->_initAction()->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {

        $this->_title($this->__('System'))
                ->_title($this->__('Locations'))
                ->_title($this->__('Regions'));

        $regionId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('SunshineBiz_Location_Model_Region');
        if ($regionId) {
            $model->setLoadAll(true)->load($regionId);
            if (!$model->getId()) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This region no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->addLocaleNames($this->getRequest()->getParam('locale'));
        } else {
            $model->setCountryId(Mage::helper('Mage_Core_Helper_Data')->getDefaultCountry());
        }

        $this->_title($model->getId() ? $model->getDefaultName() : $this->__('New Region'));
        $data = Mage::getSingleton('Mage_Backend_Model_Session')->getRegionData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('locations_region', $model);
        if ($regionId) {
            $breadcrumb = $this->__('Edit Region');
        } else {
            $breadcrumb = $this->__('New Region');
        }

        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }

    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            
            $regionId = $this->getRequest()->getParam('region_id');
            /** @var $model SunshineBiz_Location_Model_Region */
            $model = $this->_objectManager->create('SunshineBiz_Location_Model_Region')->setLoadAll(true)->load($regionId);
            if ($regionId && $model->isObjectNew()) {
                $this->_getSession()->addError($this->__('This region no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->_getSession()->addSuccess($this->__('The region has been saved.'));
                $this->_getSession()->setRegionData(false);

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
                $this->_getSession()->setRegionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('region_id')));
                return;
            }
        } 
        $this->_redirect('*/*/');
    }

    public function deleteAction() {

        if($regionId = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('SunshineBiz_Location_Model_Region')->setId($regionId)->delete();
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess($this->__('The region has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $regionId));
                return;
            }
        }
        
        Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Unable to find a region to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $regionIds = $this->getRequest()->getParam('region');
        if (!is_array($regionIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($regionIds as $regionId) {
                    Mage::getModel('SunshineBiz_Location_Model_Region')->setId($regionId)->delete();
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d region(s) were successfully deleted.', count($regionIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('Mage_Core_Model_Authorization')->isAllowed('Sunshinebiz_Location::location_regions');
    }
}