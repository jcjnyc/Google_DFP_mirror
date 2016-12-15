<?php

  /**
   * GoogleDfpAdRuleModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/InventoryService.AdRule
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpAdRuleModel extends DfpApiModel {

  public $model = array('service' => 'AdRuleService',
			'serviceMethods' => array('get'      => 'getAdRulesByStatement',
						  'create'   => 'createAdRules',
						  'update'   => 'updateAdRules',
						  'action'   => 'performAdRuleAction'
						  ),
			'table' => 'AdRule',
			'cols' => array('id'           => 'bigint(20)',  // read-only
					'name'         => 'varchar(255)', 
					'description'  => 'varchar(255)',
					'priority'     => 'int(4)',  
					'targeting'    => 'text', 
					'startDateTime' => 'datetime',
					'startDateTimeType' => 'enum("USE_START_DATE_TIME","IMMEDIATELY","ONE_HOUR_FROM_NOW")',
 					'endDateTime'   => 'datetime', 					
					'status'       => 'enum("ACTIVE","INACTIVE","DELETED","UNKNOWN")',
					'frequencyCapBehavior' => "enum('TURN_ON', 'TURN_OFF', 'DEFER', 'UNKNOWN')",
					'maxImpressionsPerLineItemPerStream' => 'int(5)',
					'preroll' => 'text', 
					'midroll' =>  'text', 
					'postroll' => 'text'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array('targeting', 'preroll', 'postroll', 'midroll' )
					),
			'pre_proc' => array('targeting'         => array('DfpUtilityModel', 'isObject'),
					    'startDateTime'     => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'       => array('DateTimeUtils','ToStringWithTimeZone'),
					    'frequencyCapBehavior' => array('DfpUtilityModel', 'isArray'),
					    'preroll' => array('DfpUtilityModel', 'isObject'),
					    'midroll' => array('DfpUtilityModel', 'isObject'),
					    'postroll' => array('DfpUtilityModel', 'isObject'),
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