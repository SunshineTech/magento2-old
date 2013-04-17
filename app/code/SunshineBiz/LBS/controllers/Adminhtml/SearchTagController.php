<?php

/**
 * SunshineBiz_LBS search tag controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Adminhtml_SearchTagController extends Mage_Backend_Controller_ActionAbstract {
    
     protected function _initAction() {
         
        $this->loadLayout()
                ->_setActiveMenu('SunshineBiz_LBS::system_lbs_searchTags')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('LBS'), $this->__('LBS'))
                ->_addBreadcrumb($this->__('SearchTags'), $this->__('SearchTags'));

        return $this;
    }

    public function indexAction() {
        
        $this->_title($this->__('System'))
                ->_title($this->__('LBS'))
                ->_title($this->__('SearchTags'));

        $this->_initAction()->renderLayout();
    }
    
    public function newAction() {
        $this->_forward('edit');
    }
    
    public function editAction() {

        $this->_title($this->__('System'))
                ->_title($this->__('LBS'))
                ->_title($this->__('SearchTags'));

        $tagId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('SunshineBiz_LBS_Model_SearchTag');
        if ($tagId) {
            $model->setLoadAll(true)->load($tagId);
            if (!$model->getId()) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This search tag no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            $model->addLocaleNames($this->getRequest()->getParam('locale'));
        }
        
        $this->_title($model->getId() ? $model->getName() : $this->__('New SearchTag'));
        $data = Mage::getSingleton('Mage_Backend_Model_Session')->getSearchTagData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('lbs_searchTag', $model);
        if (isset($tagId)) {
            $breadcrumb = $this->__('Edit SearchTag');
        } else {
            $breadcrumb = $this->__('New SearchTag');
        }

        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }
    
    protected function uploadImage($data) {
        //upload image and update the img path
        $image = $data['img'];

        // if no image was set - nothing to do
        if (empty($image) && empty($_FILES['img']['name'])) {
            return $data;
        }
        
        if (is_array($image) && !empty($image['delete'])) {
            $data['img'] = '';
            return $data;
        }
        
        if(is_array($image) && empty($_FILES['img']['name'])) {
            $data['img'] = $image['value'];            
            return $data;
        }
        
        $dir = 'lbs/searchTags/';
        try {
            $uploader = new Mage_Core_Model_File_Uploader('img');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(false);
            $result = $uploader->save(Mage::getBaseDir('media') . '/' . $dir);

            $data['img'] = $dir . $result['file'];
        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }
        }
        
        return $data;
    }

    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            
            $tagId = $this->getRequest()->getParam('id');
            /** @var $model SunshineBiz_LBS_Model_SearchTag */
            $model = $this->_objectManager->create('SunshineBiz_LBS_Model_SearchTag')->setLoadAll(true)->load($tagId);
            if ($tagId && $model->isObjectNew()) {
                $this->_getSession()->addError($this->__('This search tag no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $data = $this->uploadImage($data);
            $model->setData($data);            
            try {
                $model->save();
                $this->_getSession()->addSuccess($this->__('The search tag has been saved.'));
                $this->_getSession()->setSearchTagData(false);

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
                $this->_getSession()->setSearchTagData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        } 
        $this->_redirect('*/*/');
    }
    
    public function deleteAction() {
        
        if($tagId = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->setId($tagId)->delete();
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess($this->__('The search tag has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $tagId));
                return;
            }
        }
        
        Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Unable to find a search tag to delete.'));
        $this->_redirect('*/*/');
    }
    
    public function massChangeStatusAction() {

        $tagIds = $this->getRequest()->getParam('searchTag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($tagIds as $tagId) {
                    $model = Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->load($tagId);
                    if ($model->getId()) {
                        $model->setIsActive(!$model->getIsActive())->save();
                    }
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d search tag(s) were successfully changed status.', count($tagIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $tagIds = $this->getRequest()->getParam('searchTag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($tagIds as $tagId) {
                    Mage::getModel('SunshineBiz_LBS_Model_SearchTag')->setId($tagId)->delete();
                }
                Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
                        $this->__('Total of %d search tag(s) were successfully deleted.', count($tagIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
    
    protected function _isAllowed() {
        return Mage::getSingleton('Mage_Core_Model_Authorization')->isAllowed('SunshineBiz_LBS::lbs_searchTags');
    }
}
