<?php

  /**
   * GoogleDfpLineItemTemplateModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/LineItemTemplateService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpLineItemTemplateModel extends DfpApiModel {
  
  public $model = array('service'  => 'LineItemTemplateService',
			'serviceMethods' => array('get' => 'getLineItemTemplatesByStatement'
						  ),
			'table' => 'LineItemTemplate',
			'cols'  => array('id'  => 'bigint(20)',
					 'name' => 'varchar(255)',
					 'isDefault' => 'int(1)',
					 'lineItemName' => 'varchar(127)',
					 'targetPlatform' => 'enum("WEB","MOBILE","ANY")',
					 'enabledForSameAdvertiserException' => 'int(1)',
					 'notes' => 'text',
					 'lineItemType' => 'enum("SPONSORSHIP","STANDARD","NETWORK","BULK","PRICE_PRIORITY","HOUSE","LEGACY_DFP","CLICK_TRACKING","ADSENSE","AD_EXCHANGE","BUMPER","ADMOB","UNKNOWN")',
					 'startTime' => 'datetime',
					 'endTime'   => 'datetime',
					 'deliveryRateType' => 'enum("EVENLY","FRONTLOADED","AS_FAST_AS_POSSIBLE")',
					 'roadblockingType' => 'enum("ONLY_ONE","ONE_OR_MORE","AS_MANY_AS_POSSIBLE","ALL_ROADBLOCK","CREATIVE_SET")',
					 'creativeRotationType' => 'enum("EVEN","OPTIMIZED","MANUAL","SEQUENTIAL")'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array('notes')
					),
			'pre_proc' => array('startTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endTime'   => array('DateTimeUtils','ToStringWithTimeZone'),
					    'targetPlatform' => array('DfpUtilityModel', 'isEnum'),
					    'lineItemType' => array('DfpUtilityModel', 'isEnum'),
					    'deliveryRateType'  => array('DfpUtilityModel', 'isEnum'),
					    'roadblockingType' => array('DfpUtilityModel', 'isEnum'),
					    'creativeRotationType' => array('DfpUtilityModel', 'isEnum')
					    ),
		    'migration' => array(),
		    'create_skip' => array(),
		    'update_skip' => array()
			);

  public function __construct(){
    parent::__construct();
  }

  }