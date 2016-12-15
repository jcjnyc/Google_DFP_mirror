<?php

  /**
   * GoogleDfpContactModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ContactService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpContactModel extends DfpApiModel {
  

  public $model = array('service' => 'ContactService',
			'serviceMethods' => array('get' => 'getContactsByStatement',
						  'create' => 'createContacts',
						  'update' => 'updateContacts'
						  ),
			'table' => 'Contact',
			'cols'  => array('id'              => 'bigint(20)', 
					 'name'            => 'varchar(255)',
					 'companyId'       => 'bigint(20)',
					 'status'          => 'enum("UNINVITED","INVITE_PENDNG","INVITE_EXPIRED","INVITE_CANCELED","USER_ACTIVE","USER_DISABLED","UNKNOWN")',
					 'address'         => 'varchar(1024)',
					 'cellPhone'       => 'varchar(50)', 
					 'comment'         => 'varchar(1024)',
					 'email'           => 'varchar(255)',
					 'faxPhone'        => 'varchar(50)', 
					 'title'           => 'varchar(1024)',
					 'workPhone'       => 'varchar(50)'
					 // 'BaseContactType'            => 'varchar(255)' - depricated in 201502
					 ),
			'keys'  => array('primary key' => array( 'id' ),
					 'index' => array ('companyId', 'name'),
					 ),
			'pre_proc' => array('status' => array('DfpUtilityModel','isEnum')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',	
			'migration'	=> array(),	  
			'create_skip' => array('companyId'),
			'update_skip' => array()
			);
  
  public function __construct(){
    parent::__construct();
  }
        
  }
