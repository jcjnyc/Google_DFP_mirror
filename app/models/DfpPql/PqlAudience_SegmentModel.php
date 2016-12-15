<?php

class PqlAudience_SegmentModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Audience_Segment',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'CategoryIds'       => 'text',
					    'Id'                => 'bigint not null',
					    'Name'              => 'varchar(255)',
					    'OwnerAccountId'    => 'bigint(8)',
					    'OwnerName'         => 'varchar(255)',
					    'SegmentType'       => 'varchar(255)'
					    ),
			  'post_proc' => array (),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('Name','OwnerAccountId','OwnerName'),
					   'fulltext'    => array('CategoryIds')
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }