<?php

  /**
   * GoogleDfpCustomFieldModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CustomFieldService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCustomFieldModel extends DfpApiModel {
    
  public $model = array('service' => 'CustomFieldService',
			'serviceMethods' => array('get' => 'getCustomFieldsByStatement',
						  'create' => 'createCustomFields',
						  'update' => 'updateCustomFields'
						  ),
			'table' => 'CustomField',
		        'cols'  => array ( 'id'          => 'bigint(20) NOT NULL',
					   'name'        => 'varchar(127)',
					   'description' => 'varchar(255)',
					   'isActive'    => 'int(1)',
					   'entityType'  => 'enum("LINE_ITEM","ORDER","CREATIVE","PRODUCT_TEMPLATE","PRODUCT","PROPOSAL","PROPOSAL_LINE_ITEM","USER","UNKNOWN")',
					   'dataType'    => 'enum("STRING","NUMBER","TOGGLE","DROP_DOWN","UNKNOWN")',
					   'visibility'  => 'enum("API_ONLY","READ_ONLY","FULL")',					   
					   'options'     => 'text'
					   ),
			'keys' => array('primary key' => array( 'id' ), 
					'index'       => array('name','description'),
					'fulltext'    => array('options')
					),
			'pre_proc' => array('isActive'   => array('DfpUtilityModel', 'isBool'),
					    'options'    => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'create_skip' => array('options'),
			'update_skip' => array('options'),
			'migration' => array('options' => 'toArray')
			);

  public function __construct(){
    parent::__construct();
  }
    
  }
