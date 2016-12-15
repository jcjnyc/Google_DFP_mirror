<?php

class PqlThird_Party_CompanyModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Third_Party_Company',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'Name'              => 'varchar(255)',
					    'Type'              => 'int(1)',
					    'Status'            => 'int(1)'
					    ),
			  'post_proc' => array ('ParentIds' => 'json_encode'),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('Name'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }