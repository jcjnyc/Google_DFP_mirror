<?php

  /**
   * GoogleDfpActivityGroupModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ActivityGroupService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpActivityGroupModel extends DfpApiModel {
  
  public $model = array('service' => 'ActivityGroupService',
			'serviceMethods' => array('get' => 'getActivityGroupsByStatement',
						  'create' => 'createActivityGroups',
						  'update' => 'updateActivityGroups'
						  ),
			'table' => 'ActivityGroup',
		        'cols'  => array ( 'id'         => 'bigint(20)',
					   'name'       => 'varchar(255)',
					   'companyIds' => 'mediumtext',
					   'impressionsLookback' => 'int(2)',
					   'clicksLookback'      => 'int(2)',
					   'status'   => 'enum("ACTIVE","INACTIVE")'
					   ),
			'keys' => array('primary key' => array('id'), 
					'index'       => array('name'),
					'fulltext'    => array('companyIds')
					),
			'pre_proc' => array('companyIds' => array('DfpUtilityModel','isObject') ),
			'post_proc' => array( 'ActivityGroupToCompany' => 'self'),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('companyIds' => 'toArray'),
			'create_skip' => array('id'),
			'update_skip' => array()					
			);

  public $submodels = array('ActivityGroup_Company' => array('table' => 'ActivityGroup_Company',
							     'cols'  => array('ActivityGroupId' => 'bigint(20)',
									      'CompanyId'       => 'bigint(20)'
									      ),
							     'keys'  => array('primary key' => array('ActivityGroupId', 'CompanyId'),
									      'index' => array(),
									      'fulltext' => array()
									      ),
							     'pre_proc' => array(),
							     'engine'   => 'MyISAM',
							     'charset'  => 'utf8',
							     'collate'  => 'utf8_unicode_ci'
							     )
			    );

  public function __construct(){
    parent::__construct();
  }
  
  
  /** ActivityGroup_Company
   * 
   * expand ActivityGroup.companyIds column
   *
   **/
  public function ActivityGroup_Company(){

    $this->dbc->createOldTable( $this->submodels['ActivityGroup_Company'] );
    $this->dbc->runQuery( $this->dbc->make_table_create( $this->submodels['ActivityGroup_Company'] ) );
    $resultData = array();    
    foreach( $this->dbc->getModelCols($this->model['table'], array('id','companyIds')) as $row ){

      $companyIds = json_decode($row['companyIds']);

      foreach($companyIds as $companyId){
	$resultData[] = array('ActivityGroupId' => $row['id'],
			      'CompanyId' => $companyId );
      }
    }

    return $this->dbc->replaceRows( $this->submodels['ActivityGroup_Company']['table'], array('ActivityGroupId', 'CompanyId'), $resultData);

  }


  }
