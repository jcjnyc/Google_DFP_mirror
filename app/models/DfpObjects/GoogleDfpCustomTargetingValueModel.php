<?php

  /**
   * GoogleDfpCustomTargetingValueModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CustomTargetingService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCustomTargetingValueModel extends DfpApiModel {

  public $model = array('service' => 'CustomTargetingService',
			'serviceMethods' => array('get'      => 'getCustomTargetingValuesByStatement',
						  'create'   => 'createCustomTargetingValues',
						  'update'   => 'updateCustomTargetingValues',
						  'action'   => 'performCustomTargetingValueAction'
						  ),
			'table' => 'CustomTargetingValue',
			'cols' => array('customTargetingKeyId' => 'bigint(20)',
					'id'           => 'bigint(20)',
					'name'         => 'varchar(255)',
					'displayName'  => 'varchar(255)',
					'matchType'    => 'enum("EXACT","BROAD","PREFIX","BROAD_PREFIX","SUFFIX","CONTAINS","UNKNOWN")',
					'status'       => 'enum("ACTIVE","INACTIVE","UNKNOWN")',
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name','displayName'), 
					'fulltext'    => array()
					),
			'pre_proc' => array('matchType', array('DfpUtilityModel', 'isEnum'),
					    'status',    array('DfpUtilityModel', 'isEnum')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'create_skip' => array('id'),
			'migration'   => array(),		
			'update_skip' => array()
			); 
  
  public function __construct(){
    parent::__construct();
  }

  }