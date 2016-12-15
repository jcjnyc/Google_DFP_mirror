<?php

  /**
   * GoogleDfpContentMetadataKeyHierarchyModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ContentMetadataKeyHierarchyService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpContentMetadataKeyHierarchyModel extends DfpApiModel {
  
  public $model = array('service' => 'ContentMetadataKeyHierarchyService',
			'serviceMethods' => array('get' => 'getContentMetadataKeyHierarchiesByStatement',
						  'create' => 'createContentMetadataKeyHierarchies',
						  'update' => 'updateContentMetadataKeyHierarchies',
						  'action' => 'performContentMetadataKeyHierarchyAction'
						  ),
			'table' => 'ContentMetadataKeyHierarchy',
			'cols'  => array('id'  => 'bigint(20) NOT NULL',
					 'name' => 'varchar(255)',
					 'hierarchyLevels' => 'mediumtext',
					 'status' => 'enum("ACTIVE", "DELETED","UNKNOWN")',
					 'customTargetingKeyId' => 'bigint(10)',
					 'hierarchyLevel'     => 'int',
					 'totalResultSetSize' => 'bigint(10)',
					 'startIndex'         => 'bigint(10)',
					 'results'            => 'text'
					 ),
			'keys'   => array('primary key' => array('id')
					  ),	
			'pre_proc' => array('hierarchyLevels' => array('DfpUtilityModel', 'isObject'),
					    'results'         => array('DfpUtilityModel', 'isObject')
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
