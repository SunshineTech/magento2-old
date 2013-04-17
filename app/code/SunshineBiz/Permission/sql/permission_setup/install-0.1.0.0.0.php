<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'admin_user_role'
 */
$table = $installer->getConnection()
        ->newTable($installer->getTable('admin_user_role'))
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            ), 'User ID')
        ->addColumn('role_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            ), 'Role ID')
        ->addIndex($installer->getIdxName('admin_user_role', 'user_id'), 'user_id')
        ->addIndex($installer->getIdxName('admin_user_role', 'role_id'), 'role_id')
        ->addForeignKey($installer->getFkName('admin_user_role', 'user_id', 'admin_user', 'user_id'), 'user_id', $installer->getTable('admin_user'), 'user_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->addForeignKey($installer->getFkName('admin_user_role', 'role_id', 'admin_role', 'role_id'), 'role_id', $installer->getTable('admin_role'), 'role_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Admin User-Role Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'admin_user_rule'
 */
$table = $installer->getConnection()
        ->newTable($installer->getTable('admin_user_rule'))
        ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            ), 'Rule ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
            ), 'User ID')
        ->addColumn('resource_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => true,
            'default' => null,
            ), 'Resource ID')
        ->addColumn('privileges', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
            'nullable' => true,
            ), 'Privileges')
        ->addColumn('permission', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
            ), 'Permission')
        ->addIndex($installer->getIdxName('admin_user_rule', array('resource_id', 'user_id')), array('resource_id', 'user_id'))
        ->addIndex($installer->getIdxName('admin_user_rule', array('user_id', 'resource_id')), array('user_id', 'resource_id'))
        ->addForeignKey($installer->getFkName('admin_user_rule', 'user_id', 'admin_user', 'user_id'), 'user_id', $installer->getTable('admin_user'), 'user_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Admin User Rule Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();