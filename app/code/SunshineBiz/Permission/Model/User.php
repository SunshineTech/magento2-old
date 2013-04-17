<?php
class SunshineBiz_Permission_Model_User extends Mage_User_Model_User {
	
	public function saveUserRole($roleId) {
		
		$this->_getResource()->saveUserRole($roleId, $this);
		return $this;
	}
	
	public function clearUserRoles() {
		
		$this->_getResource()->clearUserRoles($this);
		return $this;
	}
	
	public function getUserRules() {
		return $this->_getResource()->getUserRules($this);
	}
	
	public function getRoleRules() {
		return $this->_getResource()->getRoleRules($this);
	}
	
	public function getAllRules() {
		return $this->_getResource()->getAllRules($this);
	}
}