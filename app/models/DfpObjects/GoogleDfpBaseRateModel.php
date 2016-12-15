<?php

  /**
   * GoogleDfpBaseRateModel Extends DfpApiModel
   *
   * Operate against the BaseRateService
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/BaseRateService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpBaseRateModel extends DfpApiModel {

  public $model = array('service' => 'BaseRateService',
			'serviceMethods' => array('get'      => 'getBaseRatesByStatement',
						  'create'   => 'createBaseRates',
						  'update'   => 'updateBaseRates',
						  'action'   => 'performBaseRateAction'
						  ),
			'table' => 'BaseRate',
			'cols' => array('RateCardId'  => 'bigint(20)',
					'id'          => 'bigint(20)',
					'productId'   => 'bigint(20)',
					'rate'        => 'bigint(20)'
					),
			'keys' => array('primary key' => array('id'),
					'Index'       => array('RateCardId', 'productId'), 
					'fulltext'    => array()
					),
			'pre_proc' => array('rate'    => array('DfpUtilityModel', 'isMoney')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration'  => array('dataProvider' => 'toObject'),   
            'create_skip' => array('type'),
            'update_skip' => array()
			);
  
  public function __construct(){
    parent::__construct();
  }
  
  }