<?php

  /**
   * GoogleDfpAdUnitModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/InventoryService.AdUnit
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpAdUnitModel extends DfpApiModel {

  public $model = array('service' => 'InventoryService',
			'serviceMethods' => array('get'      => 'getAdUnitsByStatement',
						  'create'   => 'createAdUnits',
						  'update'   => 'updateAdUnits',
						  'action'   => 'performAdUnitAction'
						  ),
			'table' => 'AdUnit',
			'cols' => array('id'           => 'bigint(20)',  // read-only
					'parentId'     => 'bigint(0)', // read-only once created
					'hasChildren'  => 'int(1)',  // read-only
					'name'         => 'varchar(255)', // read-only after creation, v201311 is read/write
					'description'  => 'varchar(255)',
					'parentPath'   => 'text', // read-only
					'targetWindow' => 'enum("TOP","BLANK")',
					'status'       => 'enum("ACTIVE","INACTIVE","ARCHIVED")',
					'adUnitCode'   => 'varchar(255)',  // read-only
					'adUnitSizes'  => 'mediumtext', 
					'targetPlatform' => 'enum("WEB","MOBILE","ANY")',
					'mobilePlatform' => 'enum("SITE","APPLICATION")',
					'explicitlyTargeted'       => 'int(1)', 
					'inheritedAdSenseSettings' => 'mediumtext', 
					'partnerId'                => 'bigint(20)',
					'appliedLabelFrequencyCaps'   => 'mediumtext',
					'effectiveLabelFrequencyCaps' => 'mediumtext',  // read-only
					'appliedLabels'          => 'mediumtext',
					'effectiveAppliedLabels' => 'mediumtext',  // read-only
					'effectiveTeamIds' => 'mediumtext',  // read-only
					'appliedTeamIds'   => 'mediumtext',
					'lastModifiedDateTime' => 'datetime',
					'smartSizeMode' => 'enum("UNKNOWN","NONE","SMART_BANER","DYNAMIC_SIZE")',
					'refreshRate' => 'int(3)',
					'isSharedByDistributor' => 'int(1)',
					'crossSellingDistributor' =>  'varchar(255)',
					'externalSetTopBoxChannelId' => 'varchar(255)',
					'isSetTopBoxEnabled' => 'int(1)'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name', 'parentId'), 
					'fulltext'    => array('parentPath','inheritedAdSenseSettings',
							       'appliedLabelFrequencyCaps','effectiveLabelFrequencyCaps',
							       'appliedLabels','effectiveAppliedLabels',
							       'effectiveTeamIds','appliedTeamIds')
					),
			'pre_proc' => array('parentPath'         => array('DfpUtilityModel', 'isArray'),
					    'adUnitSizes'        => array('DfpUtilityModel', 'isObject'),
					    'explicitlyTargeted' => array('DfpUtilityModel', 'isBool'),
					    'inheritedAdSenseSettings'    => array('DfpUtilityModel', 'isArray'),
					    'appliedLabelFrequencyCaps'   => array('DfpUtilityModel', 'isArray'),
					    'effectiveLabelFrequencyCaps' => array('DfpUtilityModel', 'isArray'),
					    'appliedLabels'           => array('DfpUtilityModel', 'isArray'),
					    'effectiveAppliedLabels'  => array('DfpUtilityModel', 'isArray'),
					    'effectiveTeamIds'      => array('DfpUtilityModel', 'isArray'),
					    'appliedTeamIds'        => array('DfpUtilityModel', 'isArray'),
					    'lastModifiedDateTime'  => array('DateTimeUtils','ToStringWithTimeZone'),
					    'isSharedByDistributor' => array('DfpUtilityModel', 'isBool'),
					    'isSetTopBoxEnabled'    => array('DfpUtilityModel', 'isBool')
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