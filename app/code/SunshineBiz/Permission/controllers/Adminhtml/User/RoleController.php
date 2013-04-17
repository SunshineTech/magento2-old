<?php
include_once("Mage/User/controllers/Adminhtml/User/RoleController.php");
class SunshineBiz_Permission_Adminhtml_User_RoleController extends Mage_User_Adminhtml_User_RoleController {
	
	public function saveRoleAction() {
		
		$rid        = $this->getRequest()->getParam('role_id', false);
		$resource	= $this->getRequest()->getParam('resource', false);
		$resource	= str_replace('__root__,', '', $resource);
		$resource	= str_replace(',__root__', '', $resource);
		$resource	= str_replace('__root__', '', $resource);
		$resource   = explode(',', $resource);
		$isAll = $this->getRequest()->getParam('all');
		if ($isAll) {
			$resource = array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL);
		}
	
		$role = $this->_initRole('role_id');
		if (!$role->getId() && $rid) {
			Mage::getSingleton('Mage_Backend_Model_Session')->addError($this->__('This Role no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
	
		try {
			$roleName = $this->getRequest()->getParam('rolename', false);
	
			$role->setName($roleName)
			->setPid($this->getRequest()->getParam('parent_id', false))
			->setRoleType('G');
			Mage::dispatchEvent(
			'admin_permissions_role_prepare_save',
			array('object' => $role, 'request' => $this->getRequest())
			);
			$role->save();
	
			Mage::getModel('Mage_User_Model_Rules')
			->setRoleId($role->getId())
			->setResources($resource)
			->saveRel();
			
			$this->saveUserRole($role->getId());
			
			Mage::getSingleton('Mage_Backend_Model_Session')->addSuccess(
			$this->__('The role has been successfully saved.')
			);
		} catch (Mage_Core_Exception $e) {
			Mage::getSingleton('Mage_Backend_Model_Session')->addError($e->getMessage());
		} catch (Exception $e) {
			Mage::getSingleton('Mage_Backend_Model_Session')->addError(
			$this->__('An error occurred while saving this role.')
			);
		}
	
		//$this->getResponse()->setRedirect($this->getUrl("*/*/editrole/rid/$rid"));
		$this->_redirect('*/*/');
		return;
	}
	
	protected function saveUserRole($roleId) {
		
		$oldRoleUsers = $this->getRequest()->getParam('in_role_user_old');
		parse_str($oldRoleUsers, $oldRoleUsers);
		$oldRoleUsers = array_keys($oldRoleUsers);
		
		$roleUsers  = $this->getRequest()->getParam('in_role_user', null);
		parse_str($roleUsers, $roleUsers);
		$roleUsers = array_keys($roleUsers);
		
		foreach ($roleUsers as $nRuid) {
			if(in_array($nRuid, $oldRoleUsers)) {
				//do nothing
			} else {
				$user = Mage::getModel('Mage_User_Model_User')->load($nRuid);
				$user->saveUserRole($roleId);
			}
		}
		
		foreach ($oldRoleUsers as $oUid) {
			if(in_array($oUid, $roleUsers)) {
				//do nothing
			} else {
				$this->_deleteUserFromRole($oUid, $roleId);
			}			
		}		
	}
}