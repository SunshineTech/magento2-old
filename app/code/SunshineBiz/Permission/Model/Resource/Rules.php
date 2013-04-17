<?php
class SunshineBiz_Permission_Model_Resource_Rules extends Mage_User_Model_Resource_Rules {
	
	public function saveRel(Mage_User_Model_Rules $rule) {
		
		if($rule->getRoleId() > 0) {
			$this->saveRoleRules($rule);
		} elseif ($rule->getUserId() > 0) {
			$this->saveUserRules($rule);
		}				
	}
	
	protected function saveRoleRules(Mage_User_Model_Rules $rule) {
		
		$roleId = $rule->getRoleId();
		
		$read = $this->_getReadAdapter();
		$select = $read->select()
		->from($this->getMainTable(), 'resource_id')
		->where('role_id = ?', (int) $roleId);
		$resources = $read->fetchCol($select);
		
		$postedResources = $rule->getResources();
		
		$adapter = $this->_getWriteAdapter();
		$adapter->beginTransaction();
		try {
			if ($postedResources) {
				$acl = Mage::getSingleton(
						'Mage_Core_Model_Acl_Builder',
						array(
								'areaConfig' => Mage::getConfig()->getAreaConfig(),
								'objectFactory' => Mage::getConfig()
						)
					)->getAcl();
				if ($postedResources === array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL)) {
					//do nothing
				} else {
					$postedDenyResources = array();
					foreach ($acl->getResources() as $resourceId) {
						if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
							continue;
						}
							
						if (!in_array($resourceId, $postedResources)) {
							$postedDenyResources[] = $resourceId;
						}
					}
						
					if (!$postedDenyResources) {
						$postedResources = array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL);
					}
				}
				
				$row = array(
						'role_type'   => 'G',
						'resource_id' => Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL,
						'privileges'  => '', // not used yet
						'role_id'     => $roleId,
						'permission'  => 'allow'
				);
		
				// If all was selected save it only and nothing else.
				if ($postedResources === array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL)) {
					if($resources === array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL)) {
						//do nothing
					} else {
						$condition = array(
								'role_id = ?' => (int) $roleId,
						);
						 
						$adapter->delete($this->getMainTable(), $condition);
		
						$insertData = $this->_prepareDataForTable(new Varien_Object($row), $this->getMainTable());
		
						$adapter->insert($this->getMainTable(), $insertData);
					}
				} else {
					foreach ($postedResources as $resourceId) {
						if(in_array($resourceId, $resources)) {
							//do nothing
						} else {
							$row['resource_id'] = $resourceId;
							 
							$insertData = $this->_prepareDataForTable(new Varien_Object($row), $this->getMainTable());
							$adapter->insert($this->getMainTable(), $insertData);
						}
					}
					 
					foreach ($resources as $resourceId) {
						if(in_array($resourceId, $postedResources)) {
							//do nothing
						} else {
							$condition = array(
									'role_id = ?' => (int) $roleId,
									'resource_id' => $resourceId
							);
							$adapter->delete($this->getMainTable(), $condition);
						}
					}
				}
			} elseif ($resources) {
				$condition = array(
						'role_id = ?' => (int) $roleId,
				);
				 
				$adapter->delete($this->getMainTable(), $condition);
			}
		
			$adapter->commit();
		} catch (Mage_Core_Exception $e) {
			$adapter->rollBack();
			throw $e;
		} catch (Exception $e){
			$adapter->rollBack();
			Mage::logException($e);
		}
	}

	protected function saveUserRules(Mage_User_Model_Rules $rule) {
		
		$userId = $rule->getUserId();
		$user = Mage::getModel('Mage_User_Model_User')->load($userId);
		$allRules = $user->getAllRules();
		$roleRules = $user->getRoleRules();
		$userRules = $user->getUserRules();
		
		$postedResources = $rule->getResources();
		
		$adapter = $this->_getWriteAdapter();
		$adapter->beginTransaction();
		$row = array(
				'user_id'   => $userId,
				'resource_id' => Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL,
				'permission'  => 'deny'
		);
		$userRuleTable = $this->getTable('admin_user_rule');
		try {
			if ($postedResources) {
				
				$acl = Mage::getSingleton(
						'Mage_Core_Model_Acl_Builder',
						array(
								'areaConfig' => Mage::getConfig()->getAreaConfig(),
								'objectFactory' => Mage::getConfig()
						)
				)->getAcl();
				
				$postedDenyResources = array();				
				if ($postedResources === array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL)) {
					//do nothing
				} else {
					foreach ($acl->getResources() as $resourceId) {
						if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL || in_array($resourceId, $postedResources)) {
							continue;
						}
					
						$postedDenyResources[] = $resourceId;
					}
					
					if (!$postedDenyResources) {
						$postedResources = array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL);
					}
				}				
				
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
						$allowResources[] = $allRule['resource_id'];
					}
				}
				
				if ($postedResources === array(Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL)) {//当赋予用户所有权限时
					
					if ($allResource) {
						if ($denyResources) {
							foreach ($denyResources as $denyResource) {
								$flag = true;
								foreach ($roleRules as $roleRule) {
									if ($denyResource['resource_id'] === $roleRule['resource_id']) {
										$condition = array(
												'rule_id = ?' => $denyResource['rule_id'],
										);
							
										$adapter->delete($userRuleTable, $condition);
										$flag = false;
										break;
									}
								}
							
								if ($flag) {
									$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $denyResource['rule_id']));
								}
							}
						}
					} elseif ($allowResources) {
						foreach ($userRules as $userRule) {
							//删除用户拒绝所有权限
							if ($userRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
								$condition = array(
										'rule_id = ?' => $userRule['rule_id'],
								);
									
								$adapter->delete($userRuleTable, $condition);
								break;
							}
						}
						
						$resources = $allowResources;
						foreach ($denyResources as $denyResource) {
							$flag = true;
							foreach ($roleRules as $roleRule) {
								if ($denyResource['resource_id'] === $roleRule['resource_id']) {
									$condition = array(
											'rule_id = ?' => $denyResource['rule_id'],
									);
										
									$adapter->delete($userRuleTable, $condition);
									$flag = false;
									break;
								}
							}
								
							if ($flag) {
								$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $denyResource['rule_id']));
							}
							
							$resources[] = $denyResource['resource_id'];
						}
						
						foreach ($acl->getResources() as $resourceId) {
							if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL || in_array($resourceId, $resources)) {
								continue;
							}
								
							$row['resource_id'] = $resourceId;
							$row['permission'] = 'allow';
							$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
							$adapter->insert($userRuleTable, $insertData);
						}
					} else {
						$flag = false;
						foreach ($roleRules as $roleRule) {
							if ($roleRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
								$flag = true;
								break;
							}
						}
						
						if ($flag) {
							foreach ($userRules as $userRule) {
								if ($userRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
									$condition = array(
											'rule_id = ?' => $userRule['rule_id'],
									);									
									$adapter->delete($userRuleTable, $condition);
								}
							}
								
							foreach ($denyResources as $denyResource) {
								$flag = true;
								foreach ($roleRules as $roleRule) {
									if ($denyResource['resource_id'] === $roleRule['resource_id']) {
										$condition = array(
												'rule_id = ?' => $denyResource['rule_id'],
										);
							
										$adapter->delete($userRuleTable, $condition);
										$flag = false;
										break;
									}
								}
							
								if ($flag) {
									$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $denyResource['rule_id']));
								}
							}
						} else {
							foreach ($userRules as $userRule) {
								if ($userRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
									$flag = $userRule['rule_id'];
									$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $flag));
									break;
								}
							}
							
							if ($flag) {
								$condition = array(
										'user_id = ?' => (int) $userId,
										'rule_id <> ?' => $flag,
								);
								
								$adapter->delete($userRuleTable, $condition);
							} else {
								if ($denyResources) {
									$resources = array();
									foreach ($denyResources as $denyResource) {
										$flag = true;
										foreach ($roleRules as $roleRule) {
											if ($denyResource['resource_id'] === $roleRule['resource_id']) {
												$condition = array(
														'rule_id = ?' => $denyResource['rule_id'],
												);
									
												$adapter->delete($userRuleTable, $condition);
												$flag = false;
												break;
											}
										}
									
										if ($flag) {
											$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $denyResource['rule_id']));
										}
											
										$resources[] = $denyResource['resource_id'];
									}
									
									foreach ($acl->getResources() as $resourceId) {
										if ($resourceId === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL || in_array($resourceId, $resources)) {
											continue;
										}
									
										$row['resource_id'] = $resourceId;
										$row['permission']  = 'allow';
										$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
										$adapter->insert($userRuleTable, $insertData);
									}
								} else {
									$row['permission']  = 'allow';
									$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
									$adapter->insert($userRuleTable, $insertData);
								}
							}
						}
					}
				} else {//赋予用户非所有权限时
					if ($allResource) {
						if ($denyResources) {
							foreach ($postedDenyResources as $postedDenyResource) {
								$flag = true;
								foreach ($denyResources as $denyResource) {
									if ($postedDenyResource === $denyResource['resource_id']) {
										$flag = false;
										break;
									}
								}
								//取消权限
								if ($flag) {
									foreach ($roleRules as $roleRule) {
										if ($postedDenyResource === $roleRule['resource_id']) {
											$flag = false;
											break;
										}
									}
										
									if ($flag) {
										foreach ($userRules as $userRule) {
											if ($postedDenyResource === $userRule['resource_id']) {
												$condition = array(
														'rule_id = ?' => $userRule['rule_id'],
												);
												
												$adapter->delete($userRuleTable, $condition);
												break;
											}
										}
									} else {
										foreach ($userRules as $userRule) {
											if ($postedDenyResource === $userRule['resource_id']) {
												$flag = $userRule['rule_id'];
												break;
											}
										}
									
										if ($flag) {
											$adapter->update($userRuleTable, array('permission' => 'deny'), array('rule_id = ?' => $flag));
										} else {
											$row['resource_id'] = $postedDenyResource;
											$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
											$adapter->insert($userRuleTable, $insertData);
										}
									}
								}
							}

							foreach ($denyResources as $denyResource) {
								if (in_array($denyResource['resource_id'], $postedDenyResources)) {
									continue;
								}
								//新增权限
								$flag = true;
								foreach ($roleRules as $roleRule) {
									if ($denyResource['resource_id'] === $roleRule['resource_id']) {
										$condition = array(
												'rule_id = ?' => $denyResource['rule_id'],
										);										
										$adapter->delete($userRuleTable, $condition);
										
										$flag = false;
										break;
									}
								}
								
								if ($flag) {
									$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $denyResource['rule_id']));
								}
							}
						} else {
							$flag = true;
							foreach ($roleRules as $roleRule) {
								if ($roleRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
									$flag = false;
									break;
								}
							}
								
							if ($flag) {
								foreach ($userRules as $userRule) {
									if ($userRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
										$condition = array(
												'rule_id = ?' => $userRule['rule_id'],
										);
										$adapter->delete($userRuleTable, $condition);
										break;
									}
								}
							} else {
								foreach ($userRules as $userRule) {
									if ($userRule['resource_id'] === Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL) {
										$adapter->update($userRuleTable, array('permission' => 'deny'), array('rule_id = ?' => $userRule['rule_id']));
										break;
									}
								}
							}
							
							foreach ($postedDenyResources as $postedDenyResource) {
								$flag = true;
								if (in_array($postedDenyResource, $allowResources)) {
									$flag = false;
								}
								
								if ($flag) {//新增对应拒绝权限
									$row['resource_id'] = $postedDenyResource;
									$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
									$adapter->insert($userRuleTable, $insertData);
								} else {//取消权限
									foreach ($roleRules as $roleRule) {
										if ($postedDenyResource === $roleRule['resource_id']) {
											$flag = true;
											break;
										}
									}
									
									if ($flag) {
										$flag = false;
										foreach ($userRules as $userRule) {
											if ($postedDenyResource === $userRule['resource_id']) {
												$flag = $userRule['rule_id'];
												break;
											}
										}
										
										if ($flag) {
											$adapter->update($userRuleTable, array('permission' => 'deny'), array('rule_id = ?' => $flag));
										} else {
											$row['resource_id'] = $postedDenyResource;
											$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
											$adapter->insert($userRuleTable, $insertData);
										}
									} else {
										foreach ($userRules as $userRule) {
											if ($postedDenyResource === $userRule['resource_id']) {
												$condition = array(
														'rule_id = ?' => $userRule['rule_id'],
												);
												$adapter->delete($userRuleTable, $condition);
												break;
											}
										}
									}
								}
							}
						}
					} elseif ($allowResources) {
						foreach ($allowResources as $allowResource) {
							if (in_array($allowResource, $postedResources)) {
								continue;
							}
							//取消权限	
							$flag = false;
							foreach ($roleRules as $roleRule) {
								if ($roleRule['resource_id'] === $allowResource) {
									$flag = true;
									break;
								}
							}
								
							if ($flag) {
								foreach ($userRules as $userRule) {
									if ($userRule['resource_id'] === $allowResource) {
										$adapter->update($userRuleTable, array('permission' => 'deny'), array('rule_id = ?' => $userRule['rule_id']));
										$flag = false;
										break;
									}
								}
						
								if ($flag) {
									$row['resource_id'] = $postedResource;
									$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
									$adapter->insert($userRuleTable, $insertData);
								}
							} else {
								foreach ($userRules as $userRule) {
									if ($userRule['resource_id'] === $allowResource) {
											
										$condition = array(
												'rule_id = ?' => $userRule['rule_id'],
										);
										$adapter->delete($userRuleTable, $condition);
											
										$flag = false;
										break;
									}
								}
							}
						}
						
						foreach ($postedResources as $postedResource) {
							if (in_array($postedResource, $allowResources)) {
								continue;
							}
							//新增权限
							$flag = true;
							foreach ($roleRules as $roleRule) {
								if ($postedResource === $roleRule['resource_id']) {
									$flag = false;
									break;
								}
							}
								
							if ($flag) {
								foreach ($userRules as $userRule) {
									if ($postedResource === $userRule['resource_id']) {
										$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $userRule['rule_id']));
										$flag = false;
										break;
									}
								}
								
								if ($flag) {
									$row['resource_id'] = $postedResource;
									$row['permission'] = 'allow';
									$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
									$adapter->insert($userRuleTable, $insertData);
								}
							} else {
								foreach ($userRules as $userRule) {
									if ($postedResource === $userRule['resource_id']) {
										
										$condition = array(
												'rule_id = ?' => $userRule['rule_id'],
										);
										$adapter->delete($userRuleTable, $condition);
										
										break;
									}
								}
							}
						}						
					} else {
						foreach ($postedResources as $postedResource) {
							$flag = false;
							foreach ($roleRules as $roleRule) {
								if ($postedResource === $roleRule['resource_id']) {
									$flag = true;
									break;
								}
							}
							
							if ($flag) {
								foreach ($userRules as $userRule) {
									if ($postedResource === $userRule['resource_id']) {
											
										$condition = array(
												'rule_id = ?' => $userRule['rule_id'],
										);
										$adapter->delete($userRuleTable, $condition);
											
										break;
									}
								}
							} else {
								foreach ($userRules as $userRule) {
									if ($postedResource === $userRule['resource_id']) {
										$adapter->update($userRuleTable, array('permission' => 'allow'), array('rule_id = ?' => $userRule['rule_id']));
										$flag = true;
										break;
									}
								}
								
								if ($flag) {
									$row['resource_id'] = $postedResource;
									$row['permission'] = 'allow';
									$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);
									$adapter->insert($userRuleTable, $insertData);
								}
							}
						}
					}
				}
			} elseif($allRules) {//当用户原有一些权限时,取消其所有权限
				if($roleRules) {
					foreach ($roleRules as $roleRule) {
						$flag = true;
						foreach ($userRules as $userRule) {
							//用户权限和角色权限重合时,将用户权限设置为拒绝
							if ($roleRule['resource_id'] === $userRule['resource_id']) {
								if ($userRule['permission'] === 'allow') {
									$adapter->update($userRuleTable, array('permission' => 'deny'), array('rule_id = ?' => $userRule['rule_id']));
								}
								$flag = false;
								break;
							}
						}
						//没有与角色权限相对应的用户权限时,赋予拒绝的用户权限
						if ($flag) {
							$row['resource_id'] = $roleRule['resource_id'];
							$insertData = $this->_prepareDataForTable(new Varien_Object($row), $userRuleTable);							
							$adapter->insert($userRuleTable, $insertData);
						}
					}
					
					foreach ($userRules as $userRule) {
						$flag = true;
						foreach ($roleRules as $roleRule) {
							//用户权限和角色权限重合时,前面已作处理,本处不再作处理
							if ($roleRule['resource_id'] === $userRule['resource_id']) {
								$flag = false;
								break;
							}
						}
						//没有与用户权限对应的角色权限时,取消该用户权限
						if($flag) {
							$condition = array(
									'rule_id = ?' => $userRule['rule_id'],
									'permission = ?'  => 'allow',
							);							
							$adapter->delete($userRuleTable, $condition);
						}
					}
				} else {//用户无角色权限时,删除所有用户权限
					$condition = array(
							'user_id = ?' => (int) $userId,
							'permission = ?'  => 'allow',
					);
						
					$adapter->delete($userRuleTable, $condition);
				}
			}
			
			$adapter->commit();
		} catch (Mage_Core_Exception $e) {
			$adapter->rollBack();
			throw $e;
		} catch (Exception $e){
			$adapter->rollBack();
			Mage::logException($e);
		}
	}
}