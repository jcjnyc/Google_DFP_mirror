<?php

  /**
   * GoogleDfpSharedAdUnitModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/InventoryService.SharedAdUnit
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpSharedAdUnitModel extends DfpApiModel {

  public $model = array('service' => 'SharedAdUnitService',
			'serviceMethods' => array('get'      => 'getSharedAdUnitsByStatement',
						  'action'   => 'performSharedAdUnitAction'
						  ),
			'table' => 'SharedAdUnit',
			'cols' => array('id'           => 'bigint(20)',  // read-only
					'name'         => 'varchar(255)', // read-only after creation, v201311 is read/write
					'distributorName'  => 'varchar(255)',
					'contentProviderAdUnitId'   => 'bigint(20)', // read-only
					'status'       => 'enum("PENDING","APPROVED","REJECTED","UNSHARED","PLACEHOLDER","UNKNOWN")',
					'targetPlatform' => 'enum("WEB","MOBILE","ANY")',
					'targetWindow'   => 'enum("TOP","BLANK")',
					'adUnitSizes'    => 'text'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array('adUnitSizes')
					),
			'pre_proc' => array(
					    'status'             => array('DfpUtilityModel', 'isEnum'),
					    'targetPlatform'     => array('DfpUtilityModel', 'isEnum'),
					    'targetWindow'       => array('DfpUtilityModel', 'isEnum'),
					    'adUnitSizes'        => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array(),
			'create_skip' => array(),
			'update_skip' => array()
			);
  
  
  
  public function __construct(){
    parent::__construct();
  }
    
  }