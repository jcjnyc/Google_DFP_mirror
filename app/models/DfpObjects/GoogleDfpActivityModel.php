<?php

  /**
   * GoogleDfpActivityModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ActivityService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpActivityModel extends DfpApiModel {

  public $model = array('service'  => 'ActivityService',
			'serviceMethods' => array('get' => 'getActivitiesByStatement',
						  'create' => 'createActivity',
						  'update' => 'updateActivity'),
			'table' => 'Activity',
			'cols'  => array('id'              => 'bigint(20)', 
					 'activityGroupId' => 'bigint(20)',
					 'name'            => 'varchar(255)',
					 'expectedURL'     => 'varchar(2048)',
					 'status'          => 'enum("ACTIVE","INACTIVE")',
					 'type'            => 'enum("PAGE_VIEWS","DAILY_VISITS","CUSTOM","ITEMS_PURCHASED","TRANSACTIONS","IOS_APPLICATION_DOWNLOADS","ANDROID_APPLICATION_DOWNLOADS","UNKNOWN")'
					 ),
			'keys'  => array('primary key' => array( 'id' ),
					 'index' => array ('activityGroupId', 'name'),
					 ),
			'pre_proc' => array('status' => array('DfpUtilityModel', 'isEnum') 
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
