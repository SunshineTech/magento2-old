<?php
class SunshineBiz_Permission_Block_User_Edit_Tab_Resources extends Mage_User_Block_Role_Tab_Edit {
	
	public function __construct(array $data = array()) {
		
		parent::__construct();
	
		$acl = isset($data['acl']) ? $data['acl'] : Mage::getSingleton(
				'Mage_Core_Model_Acl_Builder',
				array(
						'areaConfig' => Mage::getConfig()->getAreaConfig(),
						'objectFactory' => Mage::getConfig()
				)
		)->getAcl();
		
		$allRules = Mage::registry('permissions_user')->getAllRules();
		$allResource = null;
		$denyResources = array();
		$allowResources = array();
		foreach ($allRules as $allRule) {
			if ($allRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
				if($allRule['permission'] === 'allow') {
					$allResource = $allRule;
				}
				continue;
			}
				
			if ($allRule['permission'] === 'deny') {
				$denyResources[] = $allRule;
			} else {
				$allowResources[] = $allRule;
			}
		}
		
		if ($allResource) {
			$allRules = array();
			if ($denyResources) {
				foreach ($acl->getResources() as $resourceId) {
						
					if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
						continue;
					}
					
					$flag = true;
					foreach ($denyResources as $denyResource) {
						if ($resourceId === $denyResource['resource_id']) {
							$flag = false;
							break;
						}
					}
					
					if ($flag) {
						$allRules[] = array('resource_id' => $resourceId, 'permission' => 'allow');
					}
				}
			} else {
				$allRules = array($allResource);
			}
		} elseif ($allowResources) {
			if ($denyResources) {
				//do nothing
			} else {
				$resources = array();
				foreach ($allowResources as $allowResource) {
					$resources[] = $allowResource['resource_id'];
				}
				
				$flag = true;
				foreach ($acl->getResources() as $resourceId) {
					if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL || in_array($resourceId, $resources)) {
						continue;
					}
					
					$flag = false;
					break;
				}
				
				if ($flag) {
					$allRules = array(array('resource_id' => Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL, 'permission' => 'allow'));
				} else {
					$allRules = $allowResources;
				}
			}
		}
	
		$selectedResourceIds = array();
	
		foreach ($allRules as $item) {
			$itemResourceId = $item['resource_id'];
			if ($acl->has($itemResourceId) && $item['permission'] == 'allow') {
				array_push($selectedResourceIds, $itemResourceId);
			}
		}
	
		$this->setSelectedResources($selectedResourceIds);
	
		$this->setTemplate('Mage_User::role/edit.phtml');
	}
	
	public function getTabLabel() {
		
		return Mage::helper('SunshineBiz_Permission_Helper_Data')->__('User Resources');
	}
}
