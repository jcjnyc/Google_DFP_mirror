<?php

  /**
   * GoogleDfpUserModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/AdRuleService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpVideoAdRuleModel extends DfpApiModel {
  
  public $model = array('service'  => 'AdRuleService',
			'serviceMethods' => array('get' => 'getAdRulesByStatement'),
			'table' => 'VideoAdRule',
			'cols'  => array('id'    => 'bigint(20) NOT NULL',
					 'name'  => 'varchar(127)',
					 'priority' => 'int(5)',
					 'targeting'=> 'text', // isObject
					 'startDateTime' => 'text',  // isObject  
					 'startDateTimeType' => 'enum("USE_START_DATE_TIME", "IMMEDIATELY", "ONE_HOUR_FROM_NOW")',
					 'endDateTime' => 'text', // isObject	
					 'unlimitedEndDateTime' => 'tinyint(1)', //isBoolean 
					 'status' => 'enum("ACTIVE", "INACTIVE", "DELETED", "UNKNOWN")',
					 'frequencyCapBehavior' => 'enum("TURN_ON","TURN_OFF", "DEFER", "UNKNOWN")',
					 'maxImpressionsPerLineItemPerStream' => 'int(10)',
					 'maxImpressionsPerLineItemPerPod' => 'int(10)',
					 'preroll' => 'text', // isObject
					 'midroll' => 'text', // isObject
					 'postroll'=> 'text', // isObject
					 
					 ),
			'keys'   => array('primary key' => array('id')
					  ),
			'pre_proc' => array('targeting' => array('DfpUtilityModel', 'isObject'),
					    'startDateTime' => array('DfpUtilityModel', 'isObject'),
					    'endDateTime' => array('DfpUtilityModel', 'isObject'),
					    'unlimitedEndDateTime' => array('DfpUtilityModel', 'isBool'),
					    'preroll' => array('DfpUtilityModel', 'isObject'),
					    'midroll' => array('DfpUtilityModel', 'isObject'),
					    'postroll' => array('DfpUtilityModel', 'isObject')
					    ),
			'post_proc' => array( 
						),
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
