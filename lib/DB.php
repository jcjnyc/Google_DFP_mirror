<?php

  /** 
   * DB singleton class 
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   */

class DB {

  private $_db;
  private $schema;
  public static $_instance;
  public $lastId;
  public $debug = 0;

  private function __construct($in=0){
    if ( ( $in != 0 ) || ( defined('SQL_DEBUG') ) ){
      $this->debug = 1;
      error_log('SQL_DEBUG: ON');
      error_log('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT.','.DB_USER.','.DB_PASS);
    }
    

    $this->_db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,
			 DB_USER,
			 DB_PASS,
			 array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = "America/New_York"' )
			 );

    if ($this->debug != 0) error_log( print_r($this->_db, true) );
    
  }

  public static function getConnect(){
    if(!self::$_instance){
      self::$_instance = new self();
    }
    return self::$_instance;
  }


  public function doAction($sql=null, $in=array()){
    if ($this->debug != 0) error_log(  __METHOD__  );
    if ($this->debug != 0) error_log( $sql );
    if ($this->debug != 0) error_log( print_r($in,true) );
    
    
    $stmt = $this->_db->prepare($sql);
    
    if( count($in) > 0 ){
      
      try {
	$out =  $stmt->execute($in);
	$this->checkStmtError($stmt);
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      } catch (Exception $e) {
	echo "General Error: ".$e->getMessage();
      }

      if($this->_db->lastInsertId()){
	return $this->_db->lastInsertId();
      }else{
	return $out;
      }


    }else{

      try {
	$out =  $stmt->execute();
      } catch (PDOException $e){
	die($e->getMessage());
      }


      if($this->_db->lastInsertId()){
	return $this->_db->lastInsertId();
      }else{
	return $out;
      }
    }
  }
  
  /** runQuery
   *
   * run simple queries - if there are values in $in, i expect the query has placeholders written into it
   *
   * @param string the query itself 
   * @param array the argument list - if you are using :placeHolder style it must be associative array
   *                                  if you are using blah = ? and blahBlah = ? style it must be simple array
   * @reutrn array array of rows in associative array 
   **/
  public function runQuery($sql=null, $in=array()){
    if ($this->debug != 0) error_log( __METHOD__  );
    if ($this->debug != 0) error_log( $sql );
    if ($this->debug != 0) error_log( print_r($in,true) );
  
    $stmt = $this->_db->prepare($sql);

    if( !empty($in) ){
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


  /** getModelJoinCols
   * 
   * get joined set of columns 
   * 
   * @param array list of tables and prefix in association eg) array( 'table' => 'prefix' )
   * @param array list of columns (with prefix already included ) eg) array(t1.id, t2.id, ... )
   * @param array list of join defs and join types eg) array('t1.id = t2.subId' => ' JOIN ', 't2.name = t3.name' => 'LEFT OUTER JOIN ')
   * @param string where statement without 'where'
   * @param array list of columns to order by (make sure it has prefix!)
   * @param string limit number
   * @return array array of rows
   * 
   **/
  public function getModelJoinCols($tables, $cols, $joins, $where='', $order=array(), $limit=''){
    $table_names = array_keys($tables);
    $table_prefixes = array_values($tables);

    $c = 0;

    $sql = 'SELECT '.implode(',',$cols).' FROM ';

    foreach($joins as $join => $type){
      if ($c ==  0){
	$sql .= '`'.array_shift($table_names).'`'.' '.array_shift($table_prefixes).' '.$type.' '.array_shift($table_names).' '.array_shift($table_prefixes).' ON ( '.$join.' ) ';
	++$c;
      }else{
	$sql .= ' '.$type.' `'.array_shift($table_names).'` '.array_shift($table_prefixes).' ON ( '.$join.' ) ';
      }
    }
  
    if (!empty($where))    $sql .= ' WHERE '.$where;
    if (count($order) > 0) $sql .= ' ORDER BY '.count($order).implode(',',$order);
    if (!empty($limit))    $sql .= ' LIMIT '.$limit;

    return( $this->runQuery( $sql) );

  }
   

  /** getModelCols
   *
   * get all column data from model
   *
   * @param string table name
   * @param mixed list of columns or empty for *
   **/
  public function getModelCols($table,$cols=array('*'),$where=''){
    $select = ' SELECT '.implode(',',$cols);
    $from   = ' FROM `'.$this->getSchema().'`.`'.$table.'` ';
    if(!empty($where) && !preg_match('/WHERE/',$where) ) $where = ' WHERE '.$where; 
    
    $sql = $select.' '.$from.' '.$where;
    return( $this->runQuery( $sql ) );
  }


  /** getMaxModelCol
   *
   * get the maximum value for one column and return just the value 
   *
   * @param string table name
   * @param string column
   **/
  public function getMaxModelCol($table,$col){
    $col = 'max('.$col.') as maxCol ';
    $out = $this->getModelCols($table, array($col));
    return $out[0]['maxCol'];
  }


  /** replaceRows
   * 
   * paramater based replace rows
   *
   * @param string name of table 
   * @param array list of table columns
   * @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
   ***/
  public function replaceRows($table, $colList = array(), $data){
    if ($this->debug != 0) error_log(  __METHOD__  );
    return ( $this->placeholderQuery ( 'replace into',  $table, $colList, $data) );
  }

  /** insertRows
   *
   * parameter based insert rows
   *
   * @param string name of table 
   * @param array list of table columns
   * @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
   **/
  public function insertRows($table, $colList = array(), $data){
    if ($this->debug != 0) error_log(  __METHOD__  );
    return ( $this->placeholderQuery ( 'insert into', $table, $colList, $data) );
  }

  /** updateRows
   *
   *  paramater based update rows
   *
   * @param string name of table 
   * @param array list of table columns
   * @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
   **/
  public function updateRows($table, $colList = array(), $data){
    if ($this->debug != 0) error_log(  __METHOD__  );
    return ( $this->placeholderQuery ( 'update', $table, $colList, $data) );
  }
	


  /** placeholderQuery()
   *  replace using place holders 
   *
   * @param string table name 
   * @param array list of columns 
   * @param array AoH of data - one containing array, one row of data per line
   **/
  public function placeholderQuery($action, $table, $colList = array(), $data){
    if ($this->debug != 0) error_log(  __METHOD__  );
    if ($this->debug != 0) error_log( $action );
    if ($this->debug != 0) error_log( $table );
    if ($this->debug != 0) error_log( print_r($colList,true) );
    if ($this->debug != 0) error_log( print_r($data,true) );
      
    $x = 0;
    $c = count($colList);
    $k = array_keys($colList);

    foreach($colList as $col){
      $q[] = ':'.$col;
    }
  
    $placeHolder = implode(',',$q);

    $baseSql = $action.' `'.$this->getSchema().'`.`'.$table.'` ( `'.implode('`,`', $colList).'` ) values ( '.$placeHolder.' )';

    if ($this->debug != 0) error_log( $baseSql );

    try {

      $stmt = $this->_db->prepare($baseSql);

      foreach($data as $row){
	++$x;
	$stmt->execute($row);
	$this->checkStmtError($stmt);
      }

    }catch(PDOException $e){
      print "$e";
      exit;
    }

    if ($this->debug != 0) error_log("ROWS: $x");
    
    return($x);

  }

  /** setSchema()
   * 
   * set the schema name
   *
   * @param string name of schema to use
   **/
  public function setSchema($in){
    error_log(__METHOD__.' : '.$in);
    return $this->schema = $in;
  }

  /** getSchema()
   * 
   * get the current schema name, or return default value 
   *
   * @return string schema name 
   **/
  public function getSchema(){
    if($this->schema){
      return $this->schema; 
    }else{
      return DB_NAME;
    }
  }
  
  /** renameTable()
   * 
   * rename from table to table_new
   *
   * @param string source tabel
   * @param string target table 
   * @return status from runQuery
   **/
  public function renameTable($t1, $t2){
    if(empty($t1) || empty($t2)) die("both tables must exist");
    $sql = 'SELECT COUNT(*) TCOUNT FROM information_schema.TABLES where TABLE_SCHEMA = "'.$this->schema.'" AND TABLE_NAME = "'.$t1.'"';  
    $out = $this->runQuery( $sql );
    if( $out[0]['TCOUNT'] == 1 ){
        $sql = 'RENAME TABLE `'.$t1.'` TO `'.$t2.'`';
        error_log('--> RENAME TABLE `'.$t1.'` TO `'.$t2.'`');
        return $this->runQuery($sql);
    } else {
        error_log('--> NO PRESENTLY EXISTING TABLE: '.$t1);
        return;
    }
  }

  /** createTempTable()
   * 
   * create table from model with TEMP_ as prefix
   *
   * @param mixed model data structure with 'cols' => array() 
   * @return mixed tempTable name on success, nothing on failure
   **/
  public function createTempTable($model){
    $table = $model['table'];
    $tempTable =  'TEMP_'.$table;
    $model['table'] = $tempTable;
    $this->drop_table_by_name($tempTable); 
    if ($this->create_table_by_model($model) ){
      return $tempTable;
    }
  }

  /** createOldModel()
   *
   * move table to OLD_
   *
   * @todo should put a lock on the table so nothing starts a select
   * @return string temp table  name eg TEMP_LineItem
   **/
  public function createOldTable($model){
    $table = $model['table'];
    $oldTable =  'OLD_'.$table;
    $model['table'] = $oldTable;
    $this->drop_table_by_name($oldTable);
    $this->renameTable($table, $oldTable);
    return $oldTable;    
  }





  /** create_snapshot_table
   *
   * CREATE A 'SNAPSHOT TABLE' FROM AN EXISTING TABLE DEFINITION 
   * - so find an existing table, get the definiitons and create a new table that 
   *   prepends the columns run_id, run_date, run_time 
   *
   * @param string existing table name 
   **/
  function create_snapshot_table($in){
    
    $snapshot = 'snapshot_'.$in;
    
    // MAKE SURE TABLE DOESN'T ALREADY EXIST
    $this->drop_table_by_name($snapshot);
    
    $prefix = array( array('col' => 'run_id',
			   'definition' => 'bigint' ),
		     array('col' => 'run_date',
			   'definition' => 'date' ),
		     array('col' => 'run_time',
			   'definition' => 'time' )
		     );
    
    // GET THE EXISTING TABLE DEFINITION AND MERGEE IN PREFIX COLS
    $existing_table = $this->get_table_def($in);
    
    $table_def = array_merge($prefix, $existing_table );
    
    // CREATE THE NEW 'SNAPSHOT_' table 
    return ( $this->create_snapshot_from_def($snapshot, $table_def) );
    
  }  


  /** get_table_def()
   *
   * RETURN INFO ABOUT TABLE FROM INFORMATION_SCHEMA 
   *
   **/
  function get_table_def($in,$schema=null){
    if (!$schema ) $schema = $this->getSchema();
    
    $sql = 'select column_name as col, column_type as definition from information_schema.columns  where table_name = ? and table_schema = ? ';

    // returns an AoH
    $out = $this->runQuery($sql, array( $in,
					$schema) 
			   );
    
    if(count( $out) > 0  ){
      return($out);
    }else{
      raw('no such table '.$in.' in '.$schema);
    }
    
  }
  
  /** create_snapshot_from_def()
   *
   * BUILD CREATE TABLE FROM 
   *
   * @param string table name
   * @param array associative array of column names => definitions -- add single index sets here
   * @param string db engine 
   * @param string character set
   * @param array any multi-column or primary key sets -- not yet in use
   **/
  function create_snapshot_from_def($table, $col_def, $engine = 'MyISAM', $charset='utf8', $index = ''){
    $cols = array();
    foreach($col_def as $column){
      $cols[] = implode(' ', $column);
    }
    
    $sql = 'CREATE TABLE `'.$this->getSchema().'`.`'.$table.'`'.
      '( '.implode(',',$cols).','.
      ' index (run_id) , index (run_date) , index (run_time) '.
      ' ) '.
      ' ENGINE = '.$engine.
      ' DEFAULT CHARSET = '.$charset;
    
		
    if ( $this->doAction($sql) ){
      return (1);
    }else{
      error_log("Issue with SQL:\n");
      raw($sql);
    }
  }


  /** drop_table_by_name
   *
   * calls make_table_drop() and does drop... 
   *
   * @param string table name to drop 
   **/
  function drop_table_by_name($table){
    if ( $this->doAction( $this->make_table_drop( array('table' => $table ) ) ) ){
      return (1);
    }else{
      die("Issue with SQL".__METHOD__.$this->make_table_drop( array('table' => $table ) )."\n" );
    }
  }

  /** make_table_drop
   *
   * @param string table to drop
   * @return string drop statement with schema name prepended
   **/
   // TABLE DROP CREATOR //
   public function make_table_drop($in){
     return 'DROP TABLE IF EXISTS `'.$this->getSchema().'`.`'.$in['table'].'`';
   }


  /** create_table_by_model
   *
   * calls make_table_create() and executes ... 
   *
   * @param mixed model data structure 
   **/
  function create_table_by_model($model){
    if ( $this->doAction( $this->make_table_create( $model ) ) ){
      return (1);
    }else{
      die("Issue with SQL".__METHOD__.$this->make_table_create( $model )."\n" );
    }
  }


   /** make_table_create()
    * 
    * create a table from model definition
    * 
    * @param mixed model definition struct
    **/
   public function make_table_create($m){
     $col_list = array();
     $key_list = array();
     foreach($m['cols'] as $name => $def){
       $col_list[] = '`'.$name.'` '.$def;
     }

     foreach($m['keys'] as $type => $list){
       if($type == 'primary key'){
	 $key_list[] = $type.' (`'.implode('`,`',$list).'`)';
       }else{
	 foreach($list as $item){
	   $key_list[] = $type.' (`'.$item.'`)';
	 }
       }
     }
     $cols = implode(',',$col_list);
     $keys = implode(',',$key_list);

     // BUILD THE TABLE 
     $create = 'CREATE TABLE IF NOT EXISTS `'.$this->getSchema().'`.`'.$m['table'].'` ( '.
       $cols.
       ', '.
       $keys.
       ') '.
       ' ENGINE='.(@$m['engine'] ? $m['engine'] : 'MyISAM').
       ' DEFAULT CHARSET='.(@$m['charset'] ? $m['charset'] : 'utf8').
       ' COLLATE='.(@$m['collation'] ? $m['collation'] :    'utf8_unicode_ci');

     return($create);
   }
       

  /**
   * getAllTableDefinitions()
   * 
   **/
  public function getAllTableDefinitions(){
    foreach( $this->getTableList() as $table ){
      $out[$table] = $this->getTableDefinition($table);
    }
    
    return($out);
  }


  /** 
   * getTableList()
   *
   * get list of tables associated with present DB_NAME
   * @return array list of tables from getSchema() schema
   **/
  public function getTableList(){
    foreach( $this->runQuery('select TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?', array( $this->getSchema() ) ) as $row ){
	$out[] = $row['TABLE_NAME'];
    }
    return $out;
  }

  /** 
   * getTableDefinitions()
   *
   * get definition of TABLE from DB_NAME schema 
   *
   * @param string table name 
   **/
  public function getTableDefinition($t){
    $sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION';
    $out[$t] = $this->runQuery( $sql, array($this->getSchema(), $t) );
    return $out;
  }



   // **** UTILITY METHODS **** //

  /** dumpTableData()
   *  
   * pass through to mysql pipe command for the data in tab'd format. 
   *
   * @param string table name
   * @param string path to directory
   * @param string prefix to the output file name
   * @param string options you might want to pass to mysqldump
   * @return full path to created file
   *
   **/
  public function dumpTableData($t, $dir,$prefix = '',$opts=''){
    $out_table = $dir.'/'.$prefix.$t.'.dump';
    $sql = 'select * from '.$this->getSchema().'.'.$t;
    $mysql = sprintf('echo "%s" | mysql -u %s -p%s -h %s -P %s -N > %s', 
		     $sql, DB_USER, DB_PASS, DB_HOST, DB_PORT, 
		     $out_table);

    // CLEAR AWAY ANY OLD FILES
    if( file_exists($out_table) ){
      unlink($out_table);
    }

    // RUN COMMAND 
    `$mysql`;

    // CHECK AND RETURN THE NEW FILE
    if( file_exists($out_table) ){
      return $out_table;
    }else{
      die('problem creating '.$out_table);
    }

  }

  /**
   * quote
   *
   * quote string - do not use this - use placeholders and pdo prepare 
   *
   * @param string string to quote 
   **/
  public function quote($m){
    return $this->_db->quote($m);
  }

  /**
   * quoteTable 
   *
   * backtick quote the a table name
   *
   * @param string table name 
   **/
  public function quoteTable($t){
    return preg_match('/^`/',$t) ? $t :  '`'.$t.'`';
  }


  public function tableInfo($table){
    $sql = 'select TABLE_ROWS as count from INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME=`'.$table.'` AND TABLE_SCHEMA=`'.$this->getSchema().'`';
    foreach($this->_db->query($sql) as $row){
      print_r($row);
    }
  }



  public function getTableCreate($table){
    $sql = 'show create table '.$this->quoteTable($table);
    $out = $this->runQuery($sql);
    return( $out[0]["Create Table"] );
  }


  // thank you - 
  // http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-sequential
  public function isAssoc($arr)  {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }


  // GAG LOUDLY IF THERE IS ANY SORT OF DATA ERROR
  protected function checkStmtError($x){
    if($x->errorCode() > 0){
      raw($x->errorInfo());
    }
  }




     

  
  
  
  }
