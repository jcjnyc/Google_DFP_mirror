<?php

  /**
   * GoogleDfpCreativeWrapperModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CreativeWrapperService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCreativeWrapperModel extends DfpApiModel {
  
  public $model = array('service' => 'CreativeWrapperService',
			'serviceMethods' => array('get' => 'getCreativeWrappersByStatement',
						 'create' => 'createCreativeWrapper',
						 'update' => 'updateCreativeWrapper'
						 ),
			'table' => 'CreativeWrapper',
			'cols'  => array('id'       => 'bigint(20)',
					 'labelId'  => 'bigint(20)',
					 'header'   => 'text',
					 'footer'   => 'text',
					 'ordering' => 'enum("NO_PREFERENCE","INNER","OUTTER")',
					 'status'   => 'enum("ACTIVE","INACTIVE")'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('labelId'),
					'fulltext'    => array('header','footer')
					),
			'pre_proc' => array('header'        => array('DfpUtilityModel', 'isArray'),
					    'footer'        => array('DfpUtilityModel', 'isArray')
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
