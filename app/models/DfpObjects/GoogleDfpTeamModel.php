<?php

  /**
   * GoogleDfpTeamModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/TeamService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpTeamModel extends DfpApiModel {


  public $model = array('service'  => 'TeamService',
			'serviceMethods' => array('get' => 'getTeamsByStatement',
						  'create' => 'createTeam',
						  'update' => 'updateTeam'),
			'table' => 'Team',
			'cols' => array('id'               => 'bigint(20)',
					'name'             => 'varchar(255)',
					'description'      => 'varchar(255)',
					'hasAllCompanies'  => 'int(1)',
					'hasAllInventory'  => 'int(1)',
					'teamAccessType'   => 'enum("NONE","READ_ONLY","READ_WRITE")',
					'companyIds'       => 'mediumtext',
					'adUnitIds'        => 'mediumtext',
					'orderIds'         => 'mediumtext'),

			'keys' => array('primary key' => array( 'id' ), 
					'index'       => array('name','description'),
					'fulltext'   => array('companyIds','adUnitIds','orderIds')
					),
			'pre_proc' => array('companyIds'     => array('DfpUtilityModel', 'isArray'),
					    'adUnitIds'      => array('DfpUtilityModel', 'isArray'),
					    'orderIds'       => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('companyIds' => 'toArray', 'adUnitIds' => 'toArray'),
			'create_skip' => array('adUnitIds'),
			'update_skip' => array('adUnitIds')
			);

  public $team_company_model = array('table' => 'team_companies', 
				     'cols'  => array('teamId' => 'bigint(20) NOT NULL',
						      'companyId' => 'bigint(20) NOT NULL'
						      ),
				     'keys'  => array('primary key' => array('teamId,companyId'),
						      'index'       => array(),
						      'fulltext'    => array()
						      ),
				     'pre_proc' => array(),
				     'post_proc' => array(),
					 'engine'    => 'MyISAM',
				     'charset'   => 'utf8',
				     'collate'   => 'utf8_unicode_ci'				    
				     );	     


  public function __construct(){
    parent::__construct();
  }
  
  }