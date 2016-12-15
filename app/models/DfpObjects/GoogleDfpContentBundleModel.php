<?php

  /**
   * GoogleDfpContentBundleModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ContentBundleService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpContentBundleModel extends DfpApiModel {
  
  public $model = array('service' => 'ContentBundleService',
			'serviceMethods' => array('get' => 'getContentBundlesByStatement',
						  'create' => 'createContentBundles',
						  'update' => 'updateContentBundles',
						  'action' => 'performContentBundleAction'
						  ),
			'table' => 'ContentBundle',
			'cols'  => array('id'  => 'bigint(20) NOT NULL',
					 'name' => 'varchar(255)',
					 'description' => 'mediumtext',
					 'status' => 'enum("ACTIVE", "INACTIVE","ARCHIVED")'),
			'keys'   => array('primary key' => array('id')
					  ),	
			'pre_proc' => array(),
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
