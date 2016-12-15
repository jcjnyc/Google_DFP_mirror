<?php

  /**
   * GoogleDfpCreativeTemplateModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CreativeTemplateService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCreativeTemplateModel extends DfpApiModel {
  
  public $model = array('service' => 'CreativeTemplateService',
			'serviceMethods' => array('get' => 'getCreativeTemplatesByStatement',
						 'create' => 'createCreativeTemplate',
						 'update' => 'updateCreativeTemplate'
						 ),
			'table' => 'CreativeTemplate',
			'cols'  => array('id'            => 'bigint(20) NOT NULL',
					 'name'                 => 'varchar(255)',
					 'description'          => 'varchar(255)',
					 'variables'            => 'text',
					 'snippet'              => 'text',
					 'status'               => 'enum("ACTIVE","INACTIVE","DELETED")',
					 'type'                 => 'enum("SYSTEM_DEFINED","USER_DEFINED")',
					 'isInterstitial'       => 'int(1)',
					 'isNativeEligible'     => 'int(1)'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array('variables')
					),
			'pre_proc' => array('variables'           => array('DfpUtilityModel', 'isArray'),
					    'snippet'             => array('DfpUtilityModel', 'isObject'),
					    'isInterstitial'      => array('DfpUtilityModel', 'isBool'),
					    'isNativeEligible'    => array('DfpUtilityModel', 'isBool'),
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
