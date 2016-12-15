<?php

class PqlExchange_RateModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Exchange_Rate',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'CurrencyCode'      => 'varchar(255)',
					    'Direction'         => 'varchar(255)',
					    'ExchangeRate'      => 'bigint',
					    'Id'                => 'bigint',
					    'RefreshRate'       => 'varchar(255)'
					    ),
			  'post_proc' => array ('ParentIds' => 'json_encode'),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('CurrencyCode'),
					   'fulltext'    => array()
					   )
			  );
  
  public function __construct(){
    parent::__construct();
  }


  }