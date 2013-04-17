<?php
class SunshineBiz_Permission_Model_Acl_Loader_Rule extends Mage_User_Model_Acl_Loader_Rule {
	
	public function populateAcl(Magento_Acl $acl) {
		
		parent::populateAcl($acl);
		
		$ruleTable = $this->_resource->getTableName("admin_user_rule");		
		$adapter = $this->_resource->getConnection('read');		
		$select = $adapter->select()->from(array('r' => $ruleTable));		
		foreach ($adapter->fetchAll($select) as $rule) {
			$role = Mage_User_Model_Acl_Role_User::ROLE_TYPE . $rule['user_id'];
			$resource = $rule['resource_id'];
			$privileges = !empty($rule['privileges']) ? explode(',', $rule['privileges']) : null;
		
			if ( $rule['permission'] == 'allow') {
				if ($resource === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
					$acl->allow($role, null, $privileges);
				}
				$acl->allow($role, $resource, $privileges);
			} else if ( $rule['permission'] == 'deny' ) {
				$acl->deny($role, $resource, $privileges);
			}
		}
	}
}