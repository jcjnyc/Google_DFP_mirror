<?php

  /**
   * GoogleDfpSuggestedAdUnitModel Extends DfpApiModel
   *
   * Operate against the InventoryService specifically adUnits
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/SuggestedAdUnitService.SuggestedAdUnit
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpSuggestedAdUnitModel extends DfpApiModel {

  public $model = array('service' => 'SuggestedAdUnitService',
			'serviceMethods' => array('get'     => 'getSuggestedAdUnitsByStatement',
						  'action'  => 'preformSuggestedAdUnitAction',
						  'update'  => '',
						  'create'  => ''
						  ),
			'table' => 'SuggestedAdUnit',
			'cols' => array('id'             => 'bigint(20)',
					'numRequests'    => 'bigint(20)',
					'path'           => 'text', 
					'parentPath'     => 'text', 
					'targetWindow'   => 'enum("TOP", "BLANK")',
					'targetPlatform' => 'enum("WEB","MOBILE","ANY")',
					'suggestedAdUnitSizes' => 'mediumtext'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array(),
					'fulltext'    => array()
					),
			'pre_proc' => array('path'         => array('DfpUtilityModel', 'isArray'),
					    'parentPath'   => array('DfpUtilityModel', 'isArray'),
					    'suggestedAdUnitSizes' => array('DfpUtilityModel', 'isArray')
					    )
			,
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