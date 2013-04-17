<?php
class SunshineBiz_Permission_Model_Resource_User extends Mage_User_Model_Resource_User {
	
	protected function _afterSave(Mage_Core_Model_Abstract $user) {
		return $this;
	}
	
	public function saveUserRole($roleId, $user) {
		
		$user->setRoleId($roleId);		
		if(!$user->roleUserExists()) {
			$data = array(
					'user_id' => $user->getId(),
					'role_id' => $user->getRoleId()
					);
			$this->_getWriteAdapter()->insert($this->getTable('admin_user_role'), $data);
		}
		
		return $this;
	}
	
	public function clearUserRoles($user) {
		
		$roles = $user->getRoles();
		if (is_array($roles) && count($roles) > 0) {
			$condition = array(
					'user_id = ?' => $user->getUserId()
			);
			$this->_getWriteAdapter()->delete($this->getTable('admin_user_role'), $condition);
		}
	}
	
	public function getRoles(Mage_Core_Model_Abstract $user) {
		
		if ( !$user->getId() ) {
			return array();
		}
	
		$table  = $this->getTable('admin_user_role');
		$adapter= $this->_getReadAdapter();
	
		$select = $adapter->select()
				->from($table, 'role_id')
				->where('user_id = :user_id');
	
		$binds = array(
			'user_id' => (int) $user->getId(),
		);
	
		$roles = $adapter->fetchCol($select, $binds);
	
		if ($roles) {
			return $roles;
		}
	
		return array();
	}
	
	public function deleteFromRole(Mage_Core_Model_Abstract $user) {
		
		if ( $user->getUserId() <= 0 ) {
			return $this;
		}
		
		if ( $user->getRoleId() <= 0 ) {
			return $this;
		}
	
		$dbh = $this->_getWriteAdapter();
	
		$condition = array(
				'user_id = ?' => (int) $user->getId(),
				'role_id = ?' => (int) $user->getRoleId(),
		);
	
		$dbh->delete($this->getTable('admin_user_role'), $condition);
		
		return $this;
	}
	
	public function roleUserExists(Mage_Core_Model_Abstract $user) {
		
		if ( $user->getUserId() > 0 ) {
			
			$roleTable = $this->getTable('admin_user_role');	
			$dbh = $this->_getReadAdapter();	
			$binds = array(
					'user_id'   => $user->getUserId(),
					'role_id' => $user->getRoleId(),
			);
	
			$select = $dbh->select()->from($roleTable)
			->where('user_id = :user_id AND role_id = :role_id');
	
			return $dbh->fetchCol($select, $binds);
		} else {
			return array();
		}
	}
	
	public function hasAssigned2Role($user)	{
		
		if (is_numeric($user)) {
			$userId = $user;
		} else if ($user instanceof Mage_Core_Model_Abstract) {
			$userId = $user->getUserId();
		} else {
			return null;
		}
	
		if ( $userId > 0 ) {
			
			$adapter = $this->_getReadAdapter();	
			$select = $adapter->select();
			$select->from($this->getTable('admin_user_role'))
			->where('user_id = :user_id');
	
			$binds = array(
					'user_id' => $userId,
			);
	
			return $adapter->fetchAll($select, $binds);
		} else {
			return null;
		}
	}
	
	public function getUserRules(Mage_Core_Model_Abstract $user) {
		
		$adapter = $this->_getReadAdapter();
		$select = $adapter->select();
		$select->from($this->getTable('admin_user_rule'), array('rule_id', 'resource_id', 'permission'))
		->where('user_id = :user_id');
		
		$binds = array(
				'user_id' => $user->getId()
		);
		
		$userRules = $adapter->fetchAll($select, $binds);
		return $userRules;
	}
	
	public function getRoleRules(Mage_Core_Model_Abstract $user) {
		
		$rules = Mage::getResourceModel('Mage_User_Model_Resource_Rules_Collection')
		->addFieldToSelect(array('resource_id', 'permission'))
		->addFieldToFilter('role_id', array('in' => $user->getRoles()))->load();
		$rolesRules = array();
		foreach ($rules as $rule) {
			$rolesRules[] = array(
					'resource_id' => $rule->getResourceId(),
					'permission'  => $rule->getPermission()
					);
		}
		
		return $rolesRules;
	}
	
	public function getAllRules(Mage_Core_Model_Abstract $user) {
		
		$roleRules = $this->getRoleRules($user);
		$userRules = $this->getUserRules($user);
		
		if(count($roleRules) <= 0) {
			return $userRules;
		}
		
		if(count($userRules) <= 0) {
			return $roleRules;
		}
		
		$allRules = array();
		foreach ($roleRules as $rolesRule) {
			$flag = true;
			foreach ($userRules as $userRule) {
				if ($rolesRule['resource_id'] === $userRule['resource_id']) {
					$allRules[] = $userRule;
					$flag = false;
					break;
				}
			}
			
			if ($flag) {
				$allRules[] = $rolesRule;
			}
		}
		
		foreach ($userRules as $userRule) {
			$flag = true;
			foreach ($roleRules as $rolesRule) {
				if ($rolesRule['resource_id'] === $userRule['resource_id']) {
					$flag = false;
					break;
				}
			}
			if ($flag) {
				$allRules[] = $userRule;
			}
		}
		
		return $allRules;
	}
}