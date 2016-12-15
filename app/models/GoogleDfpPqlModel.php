<?php


class GoogleDfpPqlModel extends GoogleDfpModel {

  /**
   * PQL service 
   **/
  public $PQL; 

  /**
   * Target Type 
   **/
  public $targetType; 

  /**
   *
   **/
  public $query;

  /**
   * data returned from query 
   **/
  public $resultData;

  /**
   * model in question 
   **/ 
  protected $model;			       


  public function __construct(){
    parent::__construct();
    $this->PQL = $this->user->GetService('PublisherQueryLanguageService', GOOGLE_DFP_API_VERSION);
  }
  
  // GENERIC PQL STATEMENT 
  public function runPql(){
    $this->resultData = $this->PQL->select( $this->statement->ToStatement() );
    print_r($this->resultData);
  }


  public function importAllBrowser(){
    $count;
    $this->model  = array ('table' => 'Browser',
			   'cols'  => array( 'Id', 'BrowserName',
					     'MajorVersion','MinorVersion')
			   );

    $this->statement = new StatementBuilder();
    $this->statement->Select( implode(',',$this->model['cols']) ) ;
    $this->statement->From( $this->model['table'] );
    $this->statement->Limit( StatementBuilder::SUGGESTED_PAGE_LIMIT );
    $this->statement->Offset( 0 );
    $this->runPQL();

  }



  }