<?php
/**
 *	DataWarehouseModel 
 *    
 *      Migrate data to S3 or Redshit 
 * 
 *	@author james jackson <jamescaseyjackson@gmail.com>
 **/  

class DataWarehouseModel extends BaseModel {
  

  /*
   * db connect from BaseModel
   */
  public $dbc; 

  /*
   * helper from BaseModel
   */
  public $helper; 
  
  /*
   * REDSHIFT DATA WAREHOUSE CONNECT 
   */
  protected $pgdbc;

  /* 
   * __construct instantiate connections to data stores
   */	
  public function __construct(){
    parent::__construct();
  }
  

  /** migrateToWarehouse()
   * 
   * 
   * @param array list of tables to migrate from MySql DB to PGDB
   * @param string Google Network Id 4328 - magazines, 7351 - magazines
   * @param string path on bucket to google/dfp_api section default
   */
  public function migrateToWarehouse($tables=array(), $networkId=DFP_NETWORK_ID, $bucketPath='google/dfp_api/'){
    $tableDef;
    $colData = array();


    // USE TABLE LIST, DEFAULT IS ALL TABLES IN SCHEMA
    $tables = empty($tables) ? $this->dbc->getTableList() : $tables;

    // ADD DATE TO S3 PATH 
    $s3_path = $bucketPath.$networkId.date('/Y/m/d');
    $tdefs = array();
    foreach($tables as $t){
      $file_list[$t] = $this->dbc->dumpTableData($t,BASE_DIR.FILES_DIR);
      $tableDef = $this->dbc->getTableDefinition($t);

      // TABLE AND COLUNUM DEFS 
      foreach($tableDef[$t] as $colDef){
	$schemaDef[ $colDef['TABLE_NAME'] ][] =  array( $colDef['COLUMN_NAME'], $colDef['COLUMN_TYPE'] );
      }
    }

    // GZIP AND MOVE FILES TO LOCATION 
    foreach($file_list as $table => $file){
      $files_on_s3[$table] = $this->helper->postToS3($this->helper->gzipFile($file),
						     $s3_path);					       

      // TRANSFER THE SCHEMA FILES AS JSON 
      file_put_contents(BASE_DIR.FILES_DIR.'/'.$table.'.schema.json', 
			json_encode( array( $table => $schemaDef[ $table ] ), JSON_PRETTY_PRINT  ) 
			);
      $this->helper->postToS3(BASE_DIR.FILES_DIR.'/'.$table.'.schema.json',
						     $s3_path);					       
    }    
    
    return $files_on_s3;
    
    // COULD HAVE THIS DO THE LOAD TO A TABLE IN REDSHIFT TOO.. 
    /* 
    $this->pgdbc = PGDB::getConnect();
    foreach($files_on_s3 as $file){
      $this->pgdbc->executeCopy($s3_target_dir.'/'.$file);
    }
    */
  }

	
}