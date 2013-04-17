<?php

/**
 * SunshineBiz_Permission User controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Permission
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
include_once("Mage/User/controllers/Adminhtml/UserController.php");

class SunshineBiz_Permission_Adminhtml_UserController extends Mage_User_Adminhtml_UserController {

    public function editAction() {
        
        $this->_title($this->__('System'))
                ->_title($this->__('Permissions'))
                ->_title($this->__('Users'));

        $userId = $this->getRequest()->getParam('user_id');
        $model = Mage::getModel('Mage_User_Model_User');

        if ($userId) {
            $model->load($userId);
            if (!$model->getId()) {
                Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This user no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New User'));

        // Restore previously entered form data from session
        $data = Mage::getSingleton('Mage_Backend_Model_Session')->getUserData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('permissions_user', $model);

        if (isset($userId)) {
            $breadcrumb = $this->__('Edit User');
        } else {
            $breadcrumb = $this->__('New User');
        }
        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb);
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function saveAction() {

        $userId = $this->getRequest()->getParam('user_id');
        $resource = $this->getRequest()->getParam('resource', false);
        $resource = str_replace('__root__,', '', $resource);
        $resource = str_replace(',__root__', '', $resource);
        $resource = str_replace('__root__', '', $resource);
        $resource = explode(',', $resource);
        $isAll = $this->getRequest()->getParam('all');
        if ($isAll) {
            $resource = array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL);
        }

        $data = $this->getRequest()->getPost();
        if (!$data) {
            $this->_redirect('*/*/');
            return null;
        }

        $model = $this->_prepareUserForSave($userId, $data);

        if (is_null($model)) {
            return;
        }

        try {
            $model->save();

            Mage::getModel('Mage_User_Model_Rules')
                    ->setUserId($model->getId())
                    ->setResources($resource)
                    ->saveRel();

            $this->saveRoles($model);

            Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess($this->__('The user has been saved.'));
            Mage::getSingleton('Mage_Backend_Model_Session')->setUserData(false);
            $this->_redirect('*/*/');
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
            Mage::getSingleton('Mage_Backend_Model_Session')->setUserData($data);
            $this->_redirect('*/*/edit', array('user_id' => $model->getUserId()));
            return;
        }

        $this->_redirect('*/*/');
    }

    protected function saveRoles(Mage_Core_Model_Abstract $user) {

        $user->setExtra(unserialize($user->getExtra()));
        if ($user->hasRoles()) {
            $oldRoles = $user->getRoles();
            $roles = $user['roles'];
            foreach ($roles as $role) {
                if (in_array($role, $oldRoles)) {
                    //do nothing
                } else {
                    $user->saveUserRole($role);
                }
            }

            foreach ($oldRoles as $oldRole) {
                if (in_array($oldRole, $roles)) {
                    //do nothing
                } else {
                    $user->setRoleId($oldRole)->deleteFromRole();
                }
            }
        } else {
            $user->clearUserRoles();
        }
    }

}