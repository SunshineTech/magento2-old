<?php
$userRoles = Mage::getResourceModel('Mage_User_Model_Resource_Role_Collection')
			->addFieldToFilter('role_type', array('eq' => 'U'))
			->load();
if($userRoles && count($userRoles) > 0) {
	$array = array();
	/* @var $installer Mage_Core_Model_Resource_Setup */
	$installer = $this;
	$installer->getConnection()->beginTransaction();
	foreach ($userRoles as $userRole) {
		$array[] = array('user_id' => $userRole->getUserId(), 'role_id' => $userRole->getParentId());
		$userRole->delete();
	}
	
	
	$installer->getConnection()->insertMultiple($installer->getTable('admin_user_role'), $array);
	$installer->getConnection()->commit();
}


