<?php

  /**
   * GoogleDfpLabelModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/LabelService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpLabelModel extends DfpApiModel {
  
  public $model = array('service' => 'LabelService',
			'serviceMethods' => array('get' => 'getLabelsByStatement',
						 'create' => 'createLabel',
						 'update' => 'updateLabel'
						 ),
			'table' => 'Label',
		        'cols'  => array ( 'id'          => 'bigint(20) NOT NULL',
					   'name'        => 'varchar(127)',
					   'description' => 'varchar(255)',
					   'isActive'    => 'int(1)',
					   'types'       => 'varchar(255)',
					   ),
			'keys' => array('primary key' => array( 'id' ), 
					'index'       => array('name','description'),
					'fulltext'    => array()
					),
			'pre_proc' => array('isActive' => array('DfpUtilityModel', 'isBool'),
					    'types'    => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('types' => 'toArray'),
			'create_skip' => array(),			
			'update_skip' => array()
			);

  public function __construct(){
    parent::__construct();
  }
  
  
  }
