<?php

  /**
   * GoogleDfpCreativeSetModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CreativeSetService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCreativeSetModel extends DfpApiModel {
  
  public $model = array('service' => 'CreativeSetService',
			'serviceMethods' => array('get' => 'getCreativeSetsByStatement',
						  'create' => 'createCreativeSet',
						  'update' => 'updateCreativeSet'
						  ),
			'table' => 'CreativeSet',
			'cols'  => array('id'   => 'bigint(20) NOT NULL',
					 'name' => 'varchar(255)',
					 'masterCreativeId'     => 'bigint(20) NOT NULL',
					 'companionCreativeIds' => 'text',
					 'lastModifiedDateTime' => 'datetime'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name','masterCreativeId'),
					'fulltext'    => array('companionCreativeIds')
					),
			'pre_proc' => array('companionCreativeIds' => array('DfpUtilityModel', 'isArray'),
					    'lastModifiedDateTime' => array('DateTimeUtils',   'ToStringWithTimeZone')
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
