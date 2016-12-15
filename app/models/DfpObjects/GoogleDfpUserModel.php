<?php

  /**
   * GoogleDfpUserModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/UserService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpUserModel extends DfpApiModel {

  public $model = array('service'  => 'UserService',
			'serviceMethods' => array('get' => 'getUsersByStatement',
						  'create' => 'createUsers',
						  'update' => 'updateUsers'),
			'table' => 'User',
			'cols'  => array('id'        => 'bigint(20) NOT NULL',
					 'name'      => 'varchar(128)',
					 'email'     => 'varchar(255)',
					 'roleId'    => 'bigint(20)',
					 'roleName'  => 'varchar(255)',
					 'preferredLocale'            => 'varchar(50)', // should be [ISO language code]_[ISO_country code].
					 // 'UserRecordType'             => 'varchar(255)', NO LONGER IN v201502
					 'isActive'                   => 'int(1)',
					 'isEmailNotificationAllowed' => 'int(1)',
					 'externalId'                 => 'bigint(20)',
					 'isServiceAccount'           => 'int(1)',
					 'ordersUiLocalTimeZoneId'    => 'varchar(100)',
					 'customFieldValues'          => 'mediumtext'
					 ),
			'keys'   => array('primary key' => array('id'),
					  'index'       => array('name', 'email'),
					  'fulltext'    => array()
					  ),
			'pre_proc' => array(
    					    'isActive'          => array('DfpUtilityModel', 'isBool' ),
    					    'isEmailNotificationAllowed'  => array('DfpUtilityModel', 'isBool' ),
    					    'isServiceAccount'  => array('DfpUtilityModel', 'isBool' ),
    					    'customFieldValues' => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('isActive' => 'toBool', 'isEmailNotificationAllowed' => 'toBool', 'customFieldValues' => 'toArray' ),
			'create_skip' => array(),
			'update_skip' => array('roleId')
			);
  
  public $role_model = array('service'  => 'UserService',
			     'serviceMethods' => array('get' => 'getAllRoles'),
			     'table' => 'role_base',
			     'cols'  => array('id'   => 'bigint(20) NOT NULL',
					      'name' => 'varchar(128)',
					      'description' => 'varchar(255)'
					      ),
			     'keys'   => array('primary key' => array('id'),
					       'index' => array('name'),
					       'fulltext' => array()
					       ),
			     'pre_proc' => array(),
			     'post_proc' => array(),
			'engine'    => 'MyISAM',
			     'charset'   => 'utf8',
			     'collate'   => 'utf8_unicode_ci'
			     );
  
  
  public function __construct(){
    parent::__construct();
	
  }
  
} 
 

  
