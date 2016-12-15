<?php
/**
 * DfpPqlModel.php parent class for shared methods for all of the Google/*Model classes
 *
 * @author james jackson <jamescaseyjackson@gmail.com>
 * 
 **/
/**
 * DfpPqlModel extends GoogleDfpModel
 * 
 * methods for managing import,update and view from DFP API into local model 
 *
 **/
class DfpPqlModel extends GoogleDfpPqlModel {

  public $model; 
  public $schema;
  public $service = 'PublisherQueryLanguageService'; 
  public $where;
  public $limit;
  public $sort = 'Id ASC';
  public $class; 

  public function __construct(){
      parent::__construct();
  }

  /**
   * importAll
   * 
   * Import data for specific object
   * 
   */
  public function importAll(){
    $resultData;
    $totalResultSetSize = 0;
    $table;

    try {

      // SET DEFAULTS FOR PAGE AND STATEMENT.
      $this->service = $this->user->getService($this->model['service'], GOOGLE_DFP_API_VERSION);

      $this->statementBuilder = new StatementBuilder();
      $this->statementBuilder->Select( implode(',',array_keys($this->model['cols']) ) );
      $this->statementBuilder->From( $this->model['table'] );
      $this->statementBuilder->OrderBy($this->sort);
      $this->statementBuilder->Limit(StatementBuilder::SUGGESTED_PAGE_LIMIT);
      
      if( @$this->where ) $this->statementBuilder->Where($this->where);
      error_log(' --> `'.$this->dbc->getSchema().'`.`'.$this->model['table'].'` <--');
      
      $this->dbc->create_table_by_model($this->model);
      error_log(implode("\t",array('schema.table_name','batch','total','index')));

      // INDEX OF WHERE WE START
      $i = 0;

      do {
	$rowData = array();
	$rows = array();
	
	// GET RESULTS BY STATEMENT. - 'select' is only option for PQL
	$this->page = $this->service->select( $this->statementBuilder->ToStatement() );           
	$totalResultSetSize = count( $this->page->rows );

	if ( ( count( $this->page->rows ) > 0 ) ){
	  
	  if ( defined('DFP_LOG') ) file_put_contents(DFP_LOG.'/'.$this->model['table'].'.log', print_r($this->page,true), FILE_APPEND);
	  
	  
	  foreach( $this->page->rows as $row ){
	    $colNames = array_keys($this->model['cols']);
	    foreach( $row->values as $valueObj ){
	      $key = array_shift($colNames);
	      $rowData[ $key ] = $valueObj->value;
	    }
	    $resultData[] = $rowData;
	    $rowData = array();
	  }
	  
	  // REPLACE INTO DB
	  $this->dbc->replaceRows( $this->model['table'], array_keys($this->model['cols']), $resultData);

	  error_log(implode("\t",array($this->dbc->getSchema().'.'.$this->model['table'], count($resultData),$totalResultSetSize,$i)) );

	  // UPDATE THE INDEX SETTING 
	  $i += $totalResultSetSize;

	  // CLEAR RESULT DATA 
	  unset($resultData);

	}else{
	  // RESULT SET SIZE IS 0 
	  return;
	}

	$this->statementBuilder->IncreaseOffsetBy(StatementBuilder::SUGGESTED_PAGE_LIMIT);
      }while( $totalResultSetSize > 0);
      
      /**      // POST_PROC METHOD CALLS
	       foreach($this->model['post_proc'] as $method => $class){
	       call_user_func_array(array($class, $method),array());
	       }
      **/
      
    } catch (OAuth2Exception $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (ValidationException $e) {
      ExampleUtils::CheckForOAuth2Errors($e);
    } catch (Exception $e) {
      print $e->getMessage() . "\n";
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
