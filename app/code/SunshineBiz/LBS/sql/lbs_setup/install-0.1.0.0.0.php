<?php

/**
 * 
 * @category    SunshineBiz
 * @package     SunshineBiz_Lbs
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 Sunshine.commerce, Inc. (http://www.sunshinebiz.cn)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'lbs_search_tag'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_search_tag'))
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		'identity'  => true,			
		), 'Tag Id')
        ->addColumn('default_name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
		'nullable'  => false,
		), 'Tag Default Name')
        ->addColumn('img', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
		), 'Tag img')
        ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'default'   => 0,
		), 'Tag Priority')
        ->addColumn('normal_priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
                'unsigned'  => true,
		'default'	=> 0,
		), 'Nomal Priority')
        ->addColumn('near_priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
                'unsigned'  => true,
		'default'	=> 0,
		), 'Near Priority')
        ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'default'	=> 0,
		), 'Parent Id')
        ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		'default'	=> 0,
		), 'Is Active')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_search_tag', 'updated_at'), 'updated_at')
        ->addIndex($installer->getIdxName('lbs_search_tag', array('default_name', 'parent_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('default_name', 'parent_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->setComment('LBS Search Tag Table')
);

/**
 * Create table 'lbs_search_tag_name'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_search_tag_name'))
	->addColumn('tag_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
            ), 'Tag Id')
	->addColumn('locale', Varien_Db_Ddl_Table::TYPE_TEXT, 8, array(
            'nullable'  => false,
            'primary'   => true,
            ), 'Locale')
	->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
            'nullable'  => false,
            'default'   => null,
            ), 'Tag Name')
        ->addIndex($installer->getIdxName('lbs_search_tag_name', 'tag_id'), 'tag_id')
        ->addForeignKey($installer->getFkName('lbs_search_tag_name', 'tag_id', 'lbs_search_tag', 'id'), 
            'tag_id',  $installer->getTable('lbs_search_tag'), 'id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
	->setComment('LBS Tag Name Table')
);

/**
 * Create table 'lbs_user_tag'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_user_tag'))
	->addColumn('tag_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Tag Id')
        ->addColumn('img', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
		), 'Tag Img')
        ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'default'   => 0,
		), 'Tag Priority')        
        ->addColumn('normal_priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
                'unsigned'  => true,
		'default'	=> 0,
		), 'Nomal Priority')
        ->addColumn('near_priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
                'unsigned'  => true,
		'default'	=> 0,
		), 'Near Priority')
        ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		'default'	=> 0,
		), 'Is Active')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'unsigned'  => true,
                'nullable'  => false,
                ), 'Update Time')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_user_tag', array('tag_id', 'customer_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('tag_id', 'customer_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addIndex($installer->getIdxName('lbs_user_tag', 'customer_id'), 'customer_id')
        ->addIndex($installer->getIdxName('lbs_user_tag', 'updated_at'), 'updated_at')
        ->addForeignKey($installer->getFkName('lbs_user_tag', 'tag_id', 'lbs_search_tag', 'id'),
		'tag_id',  $installer->getTable('lbs_search_tag'), 'id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->addForeignKey($installer->getFkName('lbs_user_tag', 'customer_id', 'customer_entity', 'entity_id'),
		'customer_id',  $installer->getTable('customer_entity'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('LBS User Search Tag Table')
);

/**
 * Create table 'lbs_region_center'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_region_center'))
	->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,		
		), 'Region Id')
        ->addColumn('longitude', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		'nullable'  => false,
		), 'Region Center Longitude')
        ->addColumn('latitude', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
                'nullable'  => false,
		), 'Region Center Latitude')
        ->addColumn('map_level', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Region Center Map Level')
        ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		), 'Region Center Code')
        ->addColumn('provider', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		'nullable'  => false,
		), 'Region Center Provider')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_region_center', array('region_id', 'provider'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('region_id', 'provider'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addForeignKey($installer->getFkName('lbs_region_center', 'region_id', 'directory_country_region', 'region_id'),
		'region_id',  $installer->getTable('directory_country_region'), 'region_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('LBS Region Center Table')
);

/**
 * Create table 'lbs_area_center'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_area_center'))
	->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,		
		), 'Area Id')
        ->addColumn('longitude', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		'nullable'  => false,
		), 'Area Center Longitude')
        ->addColumn('latitude', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
                'nullable'  => false,
		), 'Area Center Latitude')
        ->addColumn('map_level', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Area Center Map Level')
        ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		), 'Area Center Code')
        ->addColumn('provider', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		'nullable'  => false,
		), 'Area Center Provider')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_area_center', array('area_id', 'provider'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('area_id', 'provider'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addForeignKey($installer->getFkName('lbs_area_center', 'area_id', 'location_area', 'id'),
		'area_id',  $installer->getTable('location_area'), 'id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('LBS Area Center Table')
);

/**
 * Create table 'lbs_region_ip'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_region_ip'))
	->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,		
		), 'Region Id')
        ->addColumn('start_ip', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false,
		), 'Region Start IP Num')
        ->addColumn('end_ip', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'nullable'  => false,
		), 'Region End IP Num')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_region_ip', array('start_ip', 'end_ip'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('start_ip', 'end_ip'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addForeignKey($installer->getFkName('lbs_region_ip', 'region_id', 'directory_country_region', 'region_id'),
		'region_id',  $installer->getTable('directory_country_region'), 'region_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('LBS Region IP Table')
);

/**
 * Create table 'lbs_area_ip'
 */
$installer->getConnection()->createTable(
        $installer->getConnection()
	->newTable($installer->getTable('lbs_area_ip'))
	->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,		
		), 'Area Id')
        ->addColumn('start_ip', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false,
		), 'Area Start IP Num')
        ->addColumn('end_ip', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'nullable'  => false,
		), 'Area End IP Num')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('lbs_area_ip', array('start_ip', 'end_ip'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('start_ip', 'end_ip'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addForeignKey($installer->getFkName('lbs_area_ip', 'area_id', 'location_area', 'id'),
		'area_id',  $installer->getTable('location_area'), 'id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('LBS Area IP Table')
);
        
$installer->endSetup();