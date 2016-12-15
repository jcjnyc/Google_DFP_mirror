<?php

class PqlGeo_TargetModel extends DfpPqlModel {

  
  public $model  = array ('table' => 'Geo_Target',
			  'service' => 'PublisherQueryLanguageService',
			  'cols'  => array( 'Id'                => 'bigint not null',
					    'Name'              => 'varchar(255)',
					    'CanonicalParentId' => 'bigint',
					    'ParentIds'         => 'varchar(255)', 
					    'CountryCode'       => 'varchar(2)', 
					    'Type' => 'enum("Airport","Autonomous_Community","Canton","City","Congressional_District","Country","County","Department","DMA_Region","Governorate","Municipality","Neighborhood","Postal_Code","Prefecture","Province","Region","State","Territory","Tv_Region","Union_Territory")',
					    'Targetable'  => 'int(1)'
					    ),
			  'post_proc' => array ('ParentIds' => 'json_encode'),
			  'keys' => array( 'primary key' => array('Id'),
					   'index'       => array('Name','CanonicalParentId','ParentIds','CountryCode','Type'),
					   'fulltext'    => array()
					   )
			  );
  
  
  
  public function __construct(){
    parent::__construct();
  }


  }