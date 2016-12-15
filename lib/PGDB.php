<?php

class PGDB {


  private $_db;
  public static $_instance;
  public $lastId;
  public $debug = 0;

  
  private function __construct($in=0){
    if ( $in != 0 ) $this->debug = $in;

    $this->_db = new PDO('pgsql:host='.PG_DB_HOST.';dbname='.PG_DB_NAME.';port='.PG_DB_PORT,
			 PG_DB_USER,
			 PG_DB_PASS);

    if ($this->debug != 0) print_r($this->_db, true);
    
  }

  public static function getConnect(){
    if(!self::$_instance){
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function tableInfo($table){
    $sql = 'select count(*) as count from '.$table;
    foreach($this->_db->query($sql) as $row){
      print_r($row);
    }
  }


  // SIMPLE QUERY - IF THERE ARE IN I EXPECT THE QUERY HAS PLACEHOLDERS
  // @param string the query itself 
  // @param array the argument list
  public function runQuery($sql=null, $in=array()){ 

    if ($this->debug != 0) print $sql ."\n";

    $stmt = $this->_db->prepare($sql);
    
    if( isset($in[0]) ){
      try { 
	$stmt->execute($in);
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      }
    }else{
      try {
	$out =  $stmt->execute();
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      }
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  // GAG LOUDLY IF THERE IS ANY SORT OF DATA ERROR
  protected function checkStmtError($x){
    if($x->errorCode() > 0){
      raw($x->errorInfo());
    }
  }

  /** createTableFromMysql()
   *
   * generate create table statement from mysql information_schema.columns data
   * 
   * @param string table name
   * @param mixed AoH of column data
   * @return string create table statement 
   **/ 
  public function createTableFromMysql($t, $data=array()){
    $out = ' CREATE TABLE '.$t.' ( ';

    foreach($data[$t] as $row){
      print_r($row);
      $out .= implode(' ',array($row['COLUMN_NAME'],$row['DATA_TYPE']) ).",\n";
    }
    $out .= ')';
    return($out);
  }

  }




