<?php

class PqlBandwidth_GroupModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Bandwidth_Group',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'BandwidthName'       => 'varchar(255)'
					    ),
			  'post_proc' => array (),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('BandwidthName'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }