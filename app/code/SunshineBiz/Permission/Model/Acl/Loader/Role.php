<?php
class SunshineBiz_Permission_Model_Acl_Loader_Role extends Mage_User_Model_Acl_Loader_Role {
	
	public function populateAcl(Magento_Acl $acl) {
		
		parent::populateAcl($acl);
		
		$adapter = $this->_resource->getConnection('read');
		$select = $adapter->select()
			->from(array('u' => $this->_resource->getTableName('admin_user')), 'user_id')
			->joinLeft(
				array('ur' => $this->_resource->getTableName('admin_user_role')),
				'u.user_id = ur.user_id',
				'role_id')
			->where('u.is_active = 1');
		foreach ($adapter->fetchAll($select) as $userRole) {
			$roleId = Mage_User_Model_Acl_Role_User::ROLE_TYPE . $userRole['user_id'];
			$parent = ($userRole['role_id'] > 0) ? Mage_User_Model_Acl_Role_Group::ROLE_TYPE . $userRole['role_id'] : null;
			if (!$acl->hasRole($roleId)) {
				$acl->addRole(
						$this->_objectFactory->getModelInstance('Mage_User_Model_Acl_Role_User', $roleId),
						$parent
				);
			} else {
				$acl->addRoleParent($roleId, $parent);
			}
		}
	}
}