<?php

  /**
   * GoogleDfpExchangeRateModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ExchangeRateService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpExchangeRateModel extends DfpApiModel {
  
  public $model = array('service' => 'ExchangeRateService',
			'serviceMethods' => array('get' => 'getExchangeRatesByStatement',
						 'create' => 'createExchangeRate',
						 'update' => 'updateExchangeRate'
						 ),
			'table' => 'ExchangeRate',
		        'cols'  => array ( 'id'           => 'bigint(20) NOT NULL',
					   'currencyCode' => 'varchar(10)',
					   'refreshRate'  => 'varchar(255)',
					   'direction'    => 'enum("TO_NETWORK","FROM_NETWORK","UNKNOWN")',
					   'exchangeRate' => 'bigint(20)'
					   ),
			'keys' => array('primary key' => array( 'id' ), 
					'index'       => array('currencyCode'),
					'fulltext'    => array()
					),
			'pre_proc' => array('direction' => array('DfpUtilityModel', 'isEnum')
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
