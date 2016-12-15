<?php

class PqlProgrammatic_BuyerModel extends DfpPqlModel {

  public $sort   = 'BuyerId';
  public $model  = array ('table'   => 'Programmatic_Buyer',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'    => array( 'AdxBuyerNetworkId' => 'bigint not null',
					      'BuyerId'           => 'varchar(255)',
					      'Name'              => 'varchar(255)'
					    ),
			  'post_proc' => array (),
			  'keys' => array( 'primary key' => array('AdxBuyerNetworkId'),
					   'index'       => array('Name'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }
  
  
  }