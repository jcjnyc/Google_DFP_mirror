<?php

  /**
   * GoogleDfpAdExclusionRuleModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/AdExclusionRuleService.AdExclusionRule
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpAdExclusionRuleModel extends DfpApiModel {

  public $model = array('service' => 'AdExclusionRuleService',
			'serviceMethods' => array('get'      => 'getAdExclusionRulesByStatement',
						  'create'   => 'createAdExclusionRules',
						  'update'   => 'updateAdExclusionRules',
						  'action'   => 'performAdExclusionRuleAction'
						  ),
			'table' => 'AdExclusionRule',
			'cols' => array('id'           => 'bigint(20)',  
					'name'         => 'varchar(255)', 
					'isActive'     => 'int(1)',
					'inventoryTargeting' => 'text',
					'isBlockAll'   => 'int(1)',
					'blockedLabelIds' => 'text',
					'allowedLabelIds' => 'text',
					'type'            => 'enum("LABEL","UNKNOWN")'
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array(),
					),
			'pre_proc' => array('isActive'  => array('DfpUtilityModel', 'isBool'),
					    'inventoryTargeting' => array('DfpUtilityModel', 'isObject'),
					    'blockedLabelIds' => array('DfpUtilityModel', 'isArray'),
					    'allowedLabelIds' => array('DfpUtilityModel', 'isArray'),
					    'type'            => array('DfpUtilityModel', 'isEnum'),
					    
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