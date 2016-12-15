<?php

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
  die("USAGE:\n\tphp importDfpByObject.php [Object|auth::Object] [methodName (default: importAll)] [targetSchema (default: ".DB_NAME.")] [ WHERE CLAUSE ]\n");
}
$model = 'GoogleDfp'.$obj.'Model';
$gdfp = new $model();


// SELECT IMPORT METHOD 
if(@$argv[2]){
  $import = $argv[2];
}else{
  $import = 'importAll';
}

// SET THE SCHEMA
@$argv[3] ? $gdfp->setSchema($argv[3]) : $gdfp->setSchema(DB_NAME);

// SET THE WHERE 
if(@$argv[4]){
  $gdfp->where = $argv[4];
}

// RUN MODEL BUILD to TEMP_ 
$gdfp->updateModelBySwap( ) ; //createModelTable();
//$gdfp->populateSubModels();



