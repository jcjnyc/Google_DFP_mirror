<?php

class REPORT_DAILY_VIEWABILITYModel extends GoogleDfpReportModel {

  public $fileLabel        = 'REPORT_DAILY_VIEWABILITY';
  public $fileFormatOptions = 'CSV_DUMP:FALSE:FALSE:FALSE';


  public $model = array('table' => 'REPORT_DAILY_VIEWABILITY',
			'cols'  => array('report_date'    => 'DATE',
					 'AD_UNIT_ID'     => 'BIGINT(11)',
					 'ORDER_ID'       => 'BIGINT(11)',
					 'LINE_ITEM_ID'   => 'BIGINT(11)',
					 'CREATIVE_ID'    => 'BIGINT(11)',
					 'AD_UNIT_NAME'   => 'VARCHAR(255)',
					 'LINE_ITEM_START_DATE_TIME' 	=> 'DATETIME',
					 'LINE_ITEM_END_DATE_TIME' 	=> 'DATETIME',
					 'LINE_ITEM_GOAL_QUANTITY' 	=> 'BIGINT(11)',
					 'LINE_ITEM_SPONSORSHIP_GOAL_PERCENTAGE' 	=> 'FLOAT(8,5)',
					 'LINE_ITEM_LIFETIME_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'LINE_ITEM_LIFETIME_CLICKS' 	        => 'BIGINT(11)',
					 'LINE_ITEM_PRIORITY' 	                => 'INT(3)',
					 'LINE_ITEM_CONTRACTED_QUANTITY' 	=> 'BIGINT(11)',
					 'LINE_ITEM_COST_PER_UNIT' 	=> 'BIGINT(11)',
					 'AD_SERVER_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_SERVER_CLICKS' 	        => 'BIGINT(11)',
					 'AD_SERVER_CTR' 	        => 'FLOAT(8,5)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS' 	        => 'BIGINT(11)',
					 'AD_EXCHANGE_LINE_ITEM_LEVEL_CTR' 	        => 'FLOAT(8,5)',
					 'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS' 	        => 'BIGINT(11)',
					 'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',
					 'AD_SERVER_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS' 	        => 'BIGINT(11)',
					 'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',
					 'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',
					 'AD_EXCHANGE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',
					 'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',
					 'TOTAL_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS' 	=> 'BIGINT(11)',
					 'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE' 	=> 'FLOAT(8,5)',

					 ),
			'keys' => array('primary key' => array('report_date',
							       'AD_UNIT_ID',
							       'LINE_ITEM_ID',
							       'CREATIVE_ID'),
					'index' => array('ORDER_ID',
							 'LINE_ITEM_START_DATE_TIME',
							 'LINE_ITEM_END_DATE_TIME'
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

  public function reportPostProcess(){
    if( file_exists( $this->reportFile ) ){
      echo "POST PROCESS: ".`sed -i 's/»/::/g' $this->reportFile`."\n";
    }
  }
    
  }