<?php

  /**
   * GoogleDfpRoleModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/RoleService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpRoleModel extends DfpApiModel {

  public $service;
  public $serviceName;
  public $where;

  public $model = array('service'  => 'UserService',
			'serviceMethods' => array('get' => 'getAllRoles',
						  'create' => '',
						  'update' => ''),
			'table' => 'Role',
			'cols'  => array('id'   => 'bigint(20) NOT NULL',
					 'name' => 'varchar(128)',
					 'description' => 'varchar(255)'
					 ),
			'keys'   => array('primary key' => array('id'),
					  'index' => array('name'),
					  'fulltext' => array()
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
  

  /**
   * importAll - for Roles
   * 
   * Import roles data - note that roles are handled differently. 
   * They are simpler objects and are called from UserService... 
   * So there is not a getRolesByStatment method or checking for cleanup pre_proc
   * 
   * @author james jackson <jamescaseyjackson@gmail.com>
   */
  // GET ROLES
  public function importAll() {
    $resultData;

    try {
      
      // Set defaults for page and statement.
      $this->service = $this->user->getService($this->model['service'], GOOGLE_DFP_API_VERSION);
      error_log(' --> `'.$this->dbc->getSchema().'`.`'.$this->model['table'].'` <--');
      $this->dbc->create_table_by_model($this->model);
      error_log(implode("\t",array('schema.table_name','batch','total','index')));

      // Get roles by statement.
      $this->page = $this->service->getAllRoles();
      foreach($this->page as $result){
	foreach( array_keys($this->model['cols']) as $item) {
	  $row[ $item ] = $result->{$item};
	}
	$resultData[] = $row;
      }

      // REPLACE INTO DB
      $this->dbc->replaceRows($this->model['table'], array_keys($this->model['cols']), $resultData);
      error_log(implode("\t",array($this->model['table'], count($this->page),count($this->page),0)) );
      unset($resultData);
      
      
    } catch (OAuth2Exception $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (ValidationException $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (Exception $e) {
      print $e->getMessage() . "\n";
    }
  }


  }
  
