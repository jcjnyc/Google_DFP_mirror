<?php

class REPORT_INTRADAY_REVENUE_DETAILModel extends GoogleDfpReportModel {

  public $fileLabel        = 'REPORT_INTRADAY_REVENUE_DETAIL';
  public $fileFormatOptions = 'CSV_DUMP:FALSE:FALSE:FALSE';

## Dimension.DATE
## AD_UNIT_ID
## ADVERTISER_ID
## ORDER_ID
## LINE_ITEM_ID
## COUNTRY_CRITERIA_ID
## AD_UNIT_NAME
## AD_UNIT_CODE
## LINE_ITEM_PRIORITY
## LINE_ITEM_LIFETIME_IMPRESSIONS
## LINE_ITEM_LIFETIME_CLICKS
## AD_SERVER_IMPRESSIONS
## AD_SERVER_CLICKS
## AD_SERVER_CTR
## AD_SERVER_CPM_AND_CPC_REVENUE
## AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS
## AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS
## AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE
## ADSENSE_LINE_ITEM_LEVEL_IMPRESSIONS
## ADSENSE_LINE_ITEM_LEVEL_CLICKS
## ADSENSE_LINE_ITEM_LEVEL_REVENUE

  public $model = array('table' => 'REPORT_INTRADAY_REVENUE_DETAIL',
			'cols'  => array('report_date' => 'DATE',
					 'AD_UNIT_ID' => 'BIGINT(11)',
					 'ADVERTISER_ID' => 'BIGINT(11)',
					 'ORDER_ID' => 'BIGINT(11)',
					 'LINE_ITEM_ID' => 'BIGINT(11)',
					 'COUNTRY_CRITERIA_ID' => 'BIGINT(11)',
					 'AD_UNIT_NAME' => 'VARCHAR(255)',
					 'AD_UNIT_CODE' => 'VARCHAR(255)',
					 'LINE_ITEM_PRIORITY'        => 'BIGINT(11)',
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
					 'ADSENSE_LINE_ITEM_LEVEL_REVENUE'         => 'BIGINT(11)',
					 ),
			'keys' => array('primary key' => array('report_date','AD_UNIT_ID','ADVERTISER_ID','ORDER_ID','LINE_ITEM_ID','COUNTRY_CRITERIA_ID','runtime'),
					'index' => array( 'COUNTRY_CRITERIA_ID',
							  'AD_UNIT_CODE',
							  'AD_UNIT_NAME'
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


  }