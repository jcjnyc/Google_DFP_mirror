<?php

  /**
   * GoogleDfpContentModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ContentService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpContentModel extends DfpApiModel {
  
  public $model = array('service' => 'ContentService',
			'serviceMethods' => array('get' => 'getContentByStatement',
						  'create' => '',
						  'update' => '',
						  'action' => ''
						  ),
			'table' => 'Content',
			'cols'  => array('id'  => 'bigint(20) NOT NULL',
					 'name' => 'varchar(255)',
					 'status' => 'enum("ACTIVE", "INACTIVE","ARCHIVED","UNKNOWN")',
					 'statusDefinedBy' => 'enum("CMS","USER")',
					 'importDateTime'  => 'datetime',
					 'lastModifiedDateTime' => 'datetime',
					 'userDefinedCustomTargetingValueIds' => 'text',
					 'mappingRuleDefinedCustomTargetingValueIds' => 'text',
					 'cmsSources' => 'text'
					 ),
			'keys'   => array('primary key' => array('id')
					  ),	
			'pre_proc'  => array(
					     'status' => array('DfpUtilityModel', 'isEnum'),
					     'statusDefinedBy'      => array('DfpUtilityModel', 'isEnum'),
					     'importDateTime'       => array('DateTimeUtils','ToStringWithTimeZone'),
					     'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					     'userDefinedCustomTargetingValueIds'        => array('DfpUtilityModel', 'isArray'),
					     'mappingRuleDefinedCustomTargetingValueIds' => array('DfpUtilityModel', 'isObject'),
					     'cmsSources' => array('DfpUtilityModel', 'isObject')
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
