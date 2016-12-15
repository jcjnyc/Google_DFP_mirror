<?php

  // *****************************************************
  // SCRIPT WILL RECREATE $model['table'] TABLE IN DB AND 
  // LOAD EACH OF THE MODELS NAMED THAT MATCH:
  //  app/models/Google/GoogleDfp[ MODEL ]Model.php 
  // *****************************************************

require_once(dirname(__DIR__).'/config/config.php');
require_once(dirname(__DIR__).'/lib/bootstrap.php');


foreach(DfpUtilityModel::listAllModels() as $model){
  $obj_name = 'GoogleDfp'.$model.'Model';
  $asObject = new $obj_name;

  $cmd = 'php '.BASE_DIR.'/cron/importDfpByObject.php '.$model;
  print `$cmd`;
  print "\n";

}
print "goodbye\n";  