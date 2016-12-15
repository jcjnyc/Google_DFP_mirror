<?php
/**
 * DfpApiModel.php parent class for shared methods for all of the Google/*Model classes
 *
 * @author james jackson <jamescaseyjackson@gmail.com>
 * 
 **/
/**
 * DfpApiModel extends GoogleDfpModel
 * 
 * methods for managing import,update and view from DFP API into local model 
 *
 **/
class DfpApiModel extends GoogleDfpModel {

  public $model; 
  public $schema;
  public $service; 
  public $where;
  public $limit = -1; // GREATER THAN ZERO WILL LIMIT THE NUMBER OF RESULTS IMPORTED
  public $sort = 'id ASC';
  public $class; 

  public $objPropList; 

  public function __construct(){
      parent::__construct();

      // INSTANTIATE SERVICE OBJECT 
      $this->service = $this->user->getService($this->model['service'], 
					       GOOGLE_DFP_API_VERSION);
  }

  /**
   * importAll
   * 
  * Import data for specific object
   * 
   */
  public function importAll(){
    $resultData;
    $totalResultSetSize;
    $table;

    try {

      // BUILD STATEMENT
      $this->statementBuilder = new StatementBuilder();
      $this->statementBuilder->OrderBy($this->sort);
      $this->statementBuilder->Limit(StatementBuilder::SUGGESTED_PAGE_LIMIT);
      if( @$this->where ) $this->statementBuilder->Where($this->where);
      if( $this->limit > 0 ) $this->statementBuilder->Limit($this->limit);

      // CREATE TABLE IF IT DOESN'T EXIST
      error_log(' --> `'.$this->dbc->getSchema().'`.`'.$this->model['table'].'` <--');
      $this->dbc->create_table_by_model($this->model);
      error_log(implode("\t",array('schema.table_name','batch','total','index')));

      // START IMPORT 
      do {
	// GET RESULTS BY STATEMENT.
	// print_r( $this->statementBuilder->ToStatement() );
	$this->page = $this->service->{$this->model['serviceMethods']['get']}( $this->statementBuilder->ToStatement() );           
	
	if ( ( isset( $this->page->results ) ) &&  ( $this->page->totalResultSetSize > 0 ) ) {
	  $this->__setInitialResultSetSize( $this->page->totalResultSetSize );
	  $totalResultSetSize = $this->limit > 0 ? $this->limit : $this->page->totalResultSetSize;
	  $i = $this->page->startIndex;
	  if ( defined('DFP_LOG') ) file_put_contents(DFP_LOG.'/'.$this->model['table'].'.log', print_r($this->page,true), FILE_APPEND);

	  foreach ($this->page->results as $result) {

	    // DEBUG CODE FOR CHECKING STRUCTURE OF OBJECTS - SAVED TO FILE AFTER PROCESING
	    if ( defined('DFP_LOG') ) {
	      foreach( array_keys( get_object_vars($result) ) as $key ){
		$this->objProp[ $key ] = 1;
	      }
	    }

	    // FOR EACH COL DEFINED IN MODEL BUILD THE RESULT DATA SET
	    foreach( array_keys( $this->model['cols'] ) as $item){
	        
	      // PRE_PROC METHOD CALLS
	      if(@isset($this->model['pre_proc'][$item]) && !empty($result->{$item}) ){
		$row[ $item ] = call_user_func_array( $this->model['pre_proc'][$item],  array($result->{$item}) ) ;
	      }elseif(empty($result->{$item})){
		$row[ $item ] = '';
	      }else{
		$row[ $item ] = $result->{$item};
	      }
	    }
	    $resultData[] = $row;
	  }
	  
	  // REPLACE INTO DB
	  $this->dbc->replaceRows( $this->model['table'], array_keys($this->model['cols']), $resultData);
	  error_log(implode("\t",array($this->dbc->getSchema().'.'.$this->model['table'], count($resultData),$totalResultSetSize,$i)) );
	  unset($resultData);

	}else{
	  // NO RESULTS TO INSERT
	  return;
	}
	
	$this->statementBuilder->IncreaseOffsetBy(StatementBuilder::SUGGESTED_PAGE_LIMIT);
      }while( $this->statementBuilder->GetOffset() < $totalResultSetSize );

      if ( defined('DFP_LOG') ) file_put_contents(DFP_LOG.'/'.$this->model['table'].'_STRUCT.log', print_r($this->objProp,true), FILE_APPEND);

      // @TODO: POST_PROC TABLE DATA HERE - INTEGRITY CHECKS,, ETC
      
    } catch (OAuth2Exception $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (ValidationException $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (Exception $e) {
      print $e->getMessage() . "\n";
      return -1;
    }
  }  


  /** updateAllModels
   *
   * update all models to current state on DFP. params really just pass through to examineModel()
   * then we iterate through objects ( default is testing for lastModifiedDateTime) those objects 
   * that contain match of $struct a
   *
   * @todo check mdf of TEMP_TABLE vs TABLE and continue if they are the same
   * @param array list of model structure to match 
   * @param int set match type. 1 == 'contains' or 0 == 'does not contain' 
   **/
  public function updateAllModels($struct = array('cols','lastModifiedDateTime'), $matches = 1 ){
    foreach( DfpUtilityModel::listAllModels() as $m ){
      $object = $this->instantiateModel($m); 
      $object->updateModel($struct,$matches);
    }
    
  }

  /** updateModel()
   * 
   * update model from within instantiated object.
   * it to see if it contains $struct, select correct update proceedure to run
   *
   * @param array list of structure to test for in $object->model 
   * @param mixed null or 1 - test for existence or non-existence of structure 
   **/
  public function updateModel($struct = array(), $matches = null ){

    // MAKE SURE THAT THE TABLE EXISTS 
    $this->dbc->create_table_by_model( $this->model );


    // EXAMINE MODEL STRUCTURE 
    $test = null;
    if( !empty($struct) ) {
      $test = $this->examineModel($this->model, $struct, $matches);
    }

    // RUN APPROPRIATE MODEL UPDATE ROUTINE 
    if( $test ){
      $this->updateModelByMatch($struct,$matches);
      $this->populateSubModels();
      return;
    }else{
      $this->updateModelBySwap();
      $this->populateSubModels();
      return;
    }

  }



  /** updateModelByMatch()
   *
   * @param object object representing one of the google models 
   * @param array test structure 
   * @param mixed null or int - test type 
   * @todo this needs to be made more generic so it can deal with differnt match criteria 
   **/
  public function updateModelByMatch($struct,$match){
    error_log("\n");
    if( array_pop($struct) == 'lastModifiedDateTime' ){
      $lmdt = $this->dbc->getMaxModelCol($this->model['table'], 'lastModifiedDateTime');
      if(!empty($lmdt)){
	error_log('UPDATED BY MATCH: '.$lmdt);

	// BULID WHERE CLAUSE FOR DFP PQL
	$lmdt = preg_replace('/ /', 'T', $lmdt);
	$this->where = " lastModifiedDateTime > '$lmdt' ";

	// RUN IMPORT
	$this->importAll();

	// POPULATE SUB MODLES IF THEY EXIST
	$this->populateSubModels();

      }else{
	error_log('WARNING -- NO LASTMODIFIEDDATETIME VALUE SET?? IS TABLE EMPTY?');
      }
    }
  }


  /** updateModelBySwap()
   * 
   * create a TEMP_ version of table and import data from DFP, then swap it for TABLE 
   *  keep backup in OLD_ version 
   *
   **/
  public function updateModelBySwap(){
    $table     = $this->model['table']; 
    error_log('UPDATED BY SWAP: '.$table);

    // CREATE TEMP_ TABLE AND IMPORT FROM DFP 
    $tempTable = $this->dbc->createTempTable($this->model);
    $this->model['table'] = $tempTable;

    // BASICALLY TRY A SECOND TIME
    $out = $this->importAll();
    if ( $out == -1 ){
      $this->importAll();
    }

    $this->model['table'] = $table;
    $oldTable = $this->dbc->createOldTable($this->model);
    $this->dbc->renameTable($tempTable, $table);

    // POPULATE SUB MODLES IF THEY EXIST
    $this->populateSubModels();
  }


  /** populateSubModels
   * 
   * populate any submodels - this should be a method attached to the 
   * primary object that expands data from within the all of the models' 
   * submodels
   *
   **/
   public function populateSubModels(){
     if( count( @$this->submodels ) > 0 ){
       error_log('SUBMODEL COUNT: '.count($this->submodels) );
       foreach($this->submodels as $modelMethod => $modelData ){
	 $this->{$modelMethod}();
       }
     }
     return;
   }


  /** examineModel()
   * 
   * test if passed in model has property or 'not property' depending on $match state
   *
   * @param array model structure
   * @param array structure to look for eg) array('cols','name')
   * @return mixed null or 1
   **/
  public function examineModel($m, $struct=array(),$matches=1){
    $tmp = $m;

    // TEST THAT IT *HAS* EACH STEP OF STRUCTURE
    if( $matches == 1){
      foreach($struct as $step ) {
	//RETURN NULL IF ANY OF THE STEPS DON'T EXIST
	if ( empty($tmp[$step] ) ){
	  return null;
	}else{
	  $tmp = $tmp[$step];
	}
      }
      return 1;
    }else{
      $x = 1;
      // TEST RETURN NULL IF BOTTOM STEP EXISTS
      foreach($struct as $step ) {
	if ( empty($tmp[$step] ) ){
	  $x = 0;
	}else{
	  $tmp=$tmp[$step];
	}
      }
      if($x == 0){
	return 1;
      }else{
	return null;
      }
    }
    die('HOW DID YOU GET HERE?');
  }


  /** createAllDfpTables()
   *
   * utility function that goes through and creates an empty model tables 
   * 
   * @todo - add sub tables as well
   **/
  public function createAllDfpTables(){
    foreach( DfpUtilityModel::listAllModels() as $m ){    
      $object = $this->instantiateModel($m);
      $object->dbc->create_table_by_model($object->model);
      error_log('CREATE IF NOT EXISTS '.$object->model['table']);
    }
  }

  /**
   * createModelTable
   * 
   * drop and create table from model
   * 
   */
  public function createModelTable(){
    $this->dbc->drop_table_by_name($this->model['table']);
    $this->dbc->create_table_by_model($this->model);
  }


  /** instantiateModel()
   * 
   * instantiate a model from string
   *
   * @param string Dfp object name 
   **/
  private function instantiateModel($m){
    // INSTANTIATE THE SKIP SET AS WELL 
    $class = 'GoogleDfp'.$m.'Model';
    return new $class;
  }


				       
  /**
   * viewObject
   * 
   * View Dfp object or objects with WHERE clause
   * 
   */
  public function viewDfpObject(){
    $resultData;
    $totalResultSetSize;
    
    try {
      
      // Set defaults for page and statement.
      $this->statementBuilder = new StatementBuilder();
      $this->statementBuilder->OrderBy($this->sort);

      if( @$this->where ) {
	$this->statementBuilder->Where($this->where);
	$this->statementBuilder->Limit(StatementBuilder::SUGGESTED_PAGE_LIMIT);
	
      }else if (@$this->limit){
	$this->statementBuilder->Limit($this->limit);
      }else{
	$this->statementBuilder->Limit(1);
      }	


      // Get results by statement.
      $this->page = $this->service->{$this->model['serviceMethods']['get']}( $this->statementBuilder->ToStatement() );           
      
      if ( isset( $this->page->results ) ) {
	print "TotalResultSetSize: ".$this->page->totalResultSetSize."\n";
	print "startIndex: ".$this->page->startIndex."\n";
	print_r($this->page->results);
      }

    } catch (OAuth2Exception $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (ValidationException $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (Exception $e) {
      print $e->getMessage() . "\n";
    }
  }  

  private function __setInitialResultSetSize( $in ){
    if( empty( $this->initialResultSet) ){
      $this->initialResultSet = $in;
    }
  }

  /**
   *  __compareResultSetSizes
   *
   * return either total difference or percentage difference from the initialResultSet 
   * 
   * @param int 
   **/
  private function __compareResultSetSizes( $in, $type='total' ){
    if( $type == 'total' ){
      return $in - $this->initialResultSet;
    }elseif( $type == 'percent' ){
      return $in / $this->initialResultSet;
    }
  }

  /** displayModel
   * 
   * print_r the public $model in question
   **/
  public function displayModel(){
    print " --> ".$this->model['table']." <--\n";
    print_r($this->model);
    print "\n\n";
  }

  }
