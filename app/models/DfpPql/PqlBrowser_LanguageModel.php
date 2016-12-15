<?php

class PqlBrowser_LanguageModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Browser_Language',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'BrowserLanguageName'       => 'varchar(255)'
					    ),
			  'post_proc' => array (),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('BrowserLanguageName'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }