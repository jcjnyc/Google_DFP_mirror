<?php

  /**
   * GoogleDfpUserTeamAssociationModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/UserTeamAssociationService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpUserTeamAssociationModel extends DfpApiModel {

  public $sort  = 'teamId,userId';
  public $model = array('service'  => 'UserTeamAssociationService',
			'serviceMethods' => array('get' => 'getUserTeamAssociationsByStatement',
						  'create' => 'createUserTeamAssociation',
						  'update' => 'updateUserTeamAssociation'),
			'table' => 'UserTeamAssociation',
			'cols' => array('teamId'               => 'bigint(20)',
					'overriddenTeamAccessType'   => 'enum("NONE","READ_ONLY","READ_WRITE")',
					'defaultTeamAccessType'      => 'enum("NONE","READ_ONLY","READ_WRITE")',
					'UserRecordTeamAssociationType' => 'varchar(100)',
					'userId'               => 'bigint(20)'),
			'keys' => array('primary key' => array( 'teamId','userId' ), 
					'index'       => array(),
					'fulltext'    => array()
					),
			'pre_proc' => array(),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array(),
			'create_skip' => array(),
			'update_skip' => array()
			);


  public function __construct(){
    parent::__construct();
  }
  

  }
