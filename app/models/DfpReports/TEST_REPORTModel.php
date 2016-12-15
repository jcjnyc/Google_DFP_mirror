<?php

class TEST_REPORTModel extends GoogleDfpReportModel {

  public $fileLabel        = 'TEST_REPORT';
  public $fileFormatOptions = 'CSV_DUMP:FALSE:FALSE:FALSE';

  public $model = array('table' => 'TEST_REPORT',
			'cols'  => array('AD_UNIT_ID'     => 'VARCHAR(255)',
					 'ADVERTISER_ID'  => 'BIGINT(11)',
					 'AD_UNIT_NAME'   => 'VARCHAR(255)',
					 'AD_SERVER_IMPRESSIONS'          => 'BIGINT(11)',
					 'AD_SERVER_CLICKS'               => 'BIGINT(11)',
					 'AD_SERVER_CTR'                  => 'BIGINT(11)',
					 'AD_SERVER_CPM_AND_CPC_REVENUE'  => 'BIGINT(11)'
					 ),
			'keys' => array('primary key' => array('AD_UNIT_ID','ADVERTISER_ID'),
					'index' => array( 'AD_UNIT_NAME'
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