<?php

  /**
   * GoogleDfpInventoryModel Extends DfpApiModel
   *
   * Operate against the PlacementService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/PlacementService.Placement
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpPlacementModel extends DfpApiModel {

  public $model = array('service' => 'PlacementService',
			'serviceMethods' => array('get'     => 'getPlacementsByStatement',
						  'create'  => 'createPlacements',
						  'update'  => 'updatePlacements',
						  'action'
						  ),
			'table' => 'Placement',
			'cols' => array(
					'targetingDescription' => 'mediumtext',
					'targetingSiteName'    => 'varchar(70)',
					'targetingAdLocation'  => 'varchar(50)',
					'id'           => 'bigint(20)',
					'name'         => 'varchar(255)',
					'description'  => 'varchar(255)',
					'placementCode' => 'varchar(255)',
					'status'       => 'enum("ACTIVE","INACTIVE","ARCHIVED")',
					'isAdSenseTargetingEnabled' => 'int(1)', // isBool
					'adSenseTargetingLocale'    => 'varchar(255)', 
					'targetedAdUnitIds'         => 'text',
					'lastModifiedDateTime' => 'datetime'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name', 'description'), 
					'fulltext'    => array('targetingDescription')
					),
			'pre_proc' => array('status'         => array('DfpUtilityModel', 'isEnum'),
					    'targetedAdUnitIds'  => array('DfpUtilityModel', 'isArray'),
					    'isAdSenseTargetingEnabled' => array('DfpUtilityModel', 'isBool'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone')
					    )
			,
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('targetedAdUnitIds' => 'toArray'),
			'create_skip' => array(),
			'update_skip' => array()
			);
  
  
  
  public function __construct(){
    parent::__construct();
  }

  }