<?php

  /**
   * GoogleDfpPremiumRateModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/PremiumRateService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpPremiumRateModel extends DfpApiModel {
  
  public $model = array('service'  => 'PremiumRateService',
			'serviceMethods' => array('get' => 'getPremiumRatesByStatement',
						  'create' => 'createPremiumRates',
						  'update' => 'updatePremiumRates'
						  ),
			'table' => 'PremiumRate',
			'cols'  => array('id'            => 'bigint(20) NOT NULL',
					 'rateCardId'    => 'bigint(20) NOT NULL',
					 'pricingMethod' => 'enum("SUM","HIGHEST","ANY_VALUE","UNKNOWN")',
					 'premiumFeature' => 'text',
					 'premiumRateValues' => 'text'

					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('rateCardId', 'pricingMethod'),
					'fulltext'    => array('premiumRateValues')
					),
			'pre_proc' => array('pricingMethod'            => array('DfpUtilityModel', 'isEnum'),
					    'premiumFeature'           => array('DfpUtilityModel', 'isObject'),
					    'premiumRateValues'        => array('DfpUtilityModel', 'isObject')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci'
			);
			

  public function __construct(){
    parent::__construct();
  }
  

  }
