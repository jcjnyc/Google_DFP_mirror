<?php

class PqlBrowserModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Browser',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'BrowserName'       => 'varchar(255)',
					    'MajorVersion'      => 'int(1)',
					    'MinorVersion'      => 'int(1)'
					    ),
			  'post_proc' => array ('ParentIds' => 'json_encode'),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('BrowserName'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }