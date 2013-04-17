<?php
class SunshineBiz_Permission_Model_Resource_Role extends Mage_User_Model_Resource_Role {	
	
	public function getRoleUsers(Mage_User_Model_Role $role) {
		
		$read = $this->_getReadAdapter();
		$binds = array(
				'roleId'   => $role->getId()
		);
	
		$select = $read->select()
			->from($this->getTable('admin_user_role'), array('user_id'))
			->where('role_id = :roleId');
	
		return $read->fetchCol($select, $binds);
	}
}