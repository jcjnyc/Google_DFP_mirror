<?php
  /**
   * importDfpPqlObject
   *
   *  create simple(ish) interface to pulling in DFP PQL objects 
   *   https://developers.google.com/doubleclick-publishers/docs/pqlreference
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   *
   **/


require_once(dirname(__DIR__).'/config/config.php');
require_once(dirname(__DIR__).'/lib/bootstrap.php');

// BUILD MODEL
if( preg_match('/::/',@$argv[1] ) ){
  list($auth,$obj) = explode('::',$argv[1],2);
  $gam = new GoogleAuthManagerModel();
  $gam->setAuthFile($auth);
}else if (@$argv[1]){
  $obj = $argv[1];
}else{
  die("USAGE:\n\tphp importDfpPqlObject.php [Object|auth::Object] [methodName (default: importAll)] [targetSchema (default: ".DB_NAME.")] [ WHERE CLAUSE ]\n");
}
$model = 'Pql'.$obj.'Model';
$gpql = new $model();

// SELECT IMPORT METHOD 
if(@$argv[2]){
  $import = $argv[2];
}else{
  $import = 'importAll';
}

// SET THE SCHEMA
@$argv[3] ? $gpql->setSchema($argv[3]) : $gpql->setSchema(DB_NAME);

// SET THE WHERE 
if(@$argv[4]){
  $gpql->where = $argv[4];
}


// RUN MODEL BUILD
//$gpql->createModelTable();

//$tableDef = new TableDefs();
//$method   = 'create_'.$gpql->model['table'].'_new';
//$tableDef->{$method}();


// RUN METHOD
$gpql->{$import}();






