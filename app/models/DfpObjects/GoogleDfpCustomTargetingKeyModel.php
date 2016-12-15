<?php

  /**
   * GoogleDfpCustomTargetingKeyModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CustomTargetingService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCustomTargetingKeyModel extends DfpApiModel {

  public $model = array('service' => 'CustomTargetingService',
			'serviceMethods' => array('get'      => 'getCustomTargetingKeysByStatement',
						  'create'   => 'createCustomTargetingKeys',
						  'update'   => 'updateCustomTargetingKeys',
						  'action'   => 'performCustomTargetingKeyAction'
						  ),
			'table' => 'CustomTargetingKey',
			'cols' => array('id'           => 'bigint(20)',
					'name'         => 'varchar(255)',
					'displayName'  => 'varchar(255)',
					'type'         => 'enum("PREDEFINED","FREEFORM")',
					'status'       => 'enum("ACTIVE","INACTIVE","UNKNOWN")'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name','displayName'), 
					'fulltext'    => array()
					),
			'pre_proc' => array('type',   array('DfpUtilityModel', 'isEnum'),
					    'status', array('DfpUtilityModel', 'isEnum')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'create_skip' => array(),
			'migration' => array(),
			'update_skip' => array()
			);
  
  public function __construct(){
    parent::__construct();
  }

  }