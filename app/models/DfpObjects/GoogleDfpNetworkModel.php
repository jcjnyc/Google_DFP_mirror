<?php

  /**
   * GoogleDfpNetworkModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/NetworkService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpNetworkModel extends DfpApiModel {

  public $service;
  public $serviceName;
  public $where;

  public $model = array('service'  => 'NetworkService',
			'serviceMethods' => array('get' => 'getAllNetworks',
						  'create' => 'makeTestNetwork',
						  'update' => 'updateNetwork'),
			'table' => 'Network',
			'cols'  => array('id'   => 'bigint(20) NOT NULL',
					 'displayName' => 'varchar(128)',
					 'networkCode' => 'varchar(100)',
					 'propertyCode' => 'varchar(100)',
					 'timeZone' => 'varchar(20)',
					 'currencyCode' => 'varchar(20)',
					 'secondaryCurrencyCodes' => 'varchar(1000)',
					 'effectiveRootAdUnitId'  => 'varchar(1000)',
					 'contentBrowseCustomTargetingKeyId' => 'bigint(20)',
					 'isTest' => 'int(1)'
					 ),
			'keys'   => array('primary key' => array('id'),
					  'index' => array('displayName'),
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
   * importAll - for Networks
   * 
   * Import networks data - note that networks are handled differently. 
   * They are simpler objects and are called from UserService... 
   * So there is not a getNetworksByStatment method or checking for cleanup pre_proc
   * 
   * @author james jackson <jamescaseyjackson@gmail.com>
   */
  // GET NETWORKS
  public function importAll() {
    $resultData;

    try {
      
      // Set defaults for page and statement.
      $this->service = $this->user->getService($this->model['service'], GOOGLE_DFP_API_VERSION);
      error_log(implode("\t",array('table name','batch','total','index')));

      // Get networks by statement.
      $this->page = $this->service->getAllNetworks();

      foreach($this->page as $result){
	foreach( array_keys($this->model['cols']) as $item) {
	  $row[ $item ] = is_array( $result->{$item} ) ? json_encode( $result->{$item} ) : $result->{$item};
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