<?php

class PqlAudience_Segment_CategoryModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Audience_Segment_Category',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'Name'              => 'varchar(255)',
					    'ParentId'          => 'bigint(8)'
					    ),
			  'post_proc' => array (),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('Name'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }