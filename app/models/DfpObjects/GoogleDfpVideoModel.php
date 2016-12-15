<?php

  /**
   * GoogleDfpUserModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ContentService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpVideoModel extends DfpApiModel {
	
  public $model = array('service'  => 'ContentService',
			'serviceMethods' => array('get' => 'getContentByStatement'),
			'table' => 'Video',
			'cols'  => array('id'    => 'bigint(20) NOT NULL',
					 'name'          => 'varchar(255)',
					 'status'        => 'enum("ACTIVE","INACTIVE","ARCHIVED", "UNKNOWN")',
					 'statusDefinedBy' => 'enum("CMS", "USER")',
					 'publishDateTime' => 'datetime',
					 'lastModifiedDateTime' => 'datetime',
					 'userDefinedCustomTargetingValueIds' => 'varchar(255)',
					 'mappingRuleDefinedCustomTargetingValueIds' => 'varchar(255)',
					 'cmsSources' => 'mediumtext'				 
					 ),
			'keys'   => array('primary key' => array('id')
					  ),
			'pre_proc' => array('status' => array('DfpUtilityModel', 'isEnum'),
					    'statusDefinedBy'  => array('DfpUtilityModel', 'isEnum'),
					    'publishDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'mappingRuleDefinedCustomTargetingValueIds'  => array('DfpUtilityModel', 'isArray'),
					    'cmsSources' => array('DfpUtilityModel', 'isArray')
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
	