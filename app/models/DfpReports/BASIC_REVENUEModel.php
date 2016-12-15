<?php

class BASIC_REVENUEModel extends GoogleDfpReportModel {

  public $fileLabel        = 'BASIC_REVENUE';
  public $fileFormatOptions = 'CSV_DUMP:FALSE:FALSE:FALSE';

  public $model = array('table' => 'BASIC_REVENUE',
			'cols'  => array('AD_UNIT_ID'     => 'VARCHAR(255)',
					 'COUNTRY_CRITERIA_ID' => 'BIGINT(11)',
					 'ADVERTISER_ID'  => 'BIGINT(11)',
					 'ORDER_ID'       => 'BIGINT(11)',
					 'LINE_ITEM_ID'   => 'BIGINT(11)',
					 'LINE_ITEM_TYPE' => 'VARCHAR(255)',
					 'AD_UNIT_CODE'   => 'VARCHAR(255)',
					 'ORDER_AGENCY'   => 'VARCHAR(255)',
					 'LINE_ITEM_LIFETIME_IMPRESSIONS' => 'BIGINT(11)',
					 'LINE_ITEM_LIFETIME_CLICKS'      => 'BIGINT(11)',
					 'AD_SERVER_IMPRESSIONS'          => 'BIGINT(11)',
					 'AD_SERVER_CLICKS'               => 'BIGINT(11)',
					 'AD_SERVER_CTR'                  => 'BIGINT(11)',
					 'AD_SERVER_CPM_AND_CPC_REVENUE'  => 'BIGINT(11)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS' => 'BIGINT(11)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS'      => 'BIGINT(11)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE'     => 'BIGINT(11)',
					 'ADSENSE_LINE_ITEM_LEVEL_IMPRESSIONS'     => 'BIGINT(11)',
					 'ADSENSE_LINE_ITEM_LEVEL_CLICKS'          => 'BIGINT(11)',
					 'ADSENSE_LINE_ITEM_LEVEL_REVENUE'         => 'BIGINT(11)'
					 ),
			'keys' => array('primary key' => array('AD_UNIT_ID','COUNTRY_CRITERIA_ID','ADVERTISER_ID',     
							       'ORDER_ID','LINE_ITEM_ID'),
					'index' => array( 'AD_UNIT_CODE',
							  'ORDER_AGENCY'
							  ),
					'fulltext' => array()
					),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci'
			);

  public function __construct(){
    parent::__construct();
  }


  // SHOULDN'T NEED ANY PARSING REALLY 
  protected function parseReportLine($in){
    return( explode(',', $in ) );    
  }


  }