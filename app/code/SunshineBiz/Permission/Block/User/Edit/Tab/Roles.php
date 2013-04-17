<?php
class SunshineBiz_Permission_Block_User_Edit_Tab_Roles extends Mage_User_Block_User_Edit_Tab_Roles {
	
	public function __construct() {
		$this->setDefaultFilter(array('assigned_user_role'=>1));
		parent::__construct();
	}
	
	protected function _prepareColumns() {
	
		$this->addColumn('assigned_user_role', array(
            'header_css_class' => 'a-center',
            'header'    => Mage::helper('Mage_User_Helper_Data')->__('Assigned'),
            'type'      => 'checkbox',
            'field_name' => 'roles[]',
            'values'    => $this->_getSelectedRoles(),
            'align'     => 'center',
            'index'     => 'role_id'
        ));
	
		$this->addColumn('role_name', array(
				'header'    =>Mage::helper('Mage_User_Helper_Data')->__('Role Name'),
				'index'     =>'role_name'
		));
	
		return $this;
	}
}