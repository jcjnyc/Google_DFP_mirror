<?php
  /**
   * exportToDataWarehouse
   *
   * script takes single argument of schema name and dumps all tables from that schema to the 
   *  local BASE_DIR.FILES_DIR directory and then gzips them and moves them to 
   *  S3_BUCKET/google/dfp_api/#NETWORKNUM 
   *
   **/

require_once(dirname(__DIR__).'/config/config.php');
require_once(dirname(__DIR__).'/lib/bootstrap.php');

$dw  = new DataWarehouseModel();

// SOURCE SCHEMA 
if($argv[1]){
  $dw->setSchema($argv[1]);
}

// ALTER THE NETWORK ID 
if($argv[2]){
  $networkId = $argv[2];
}else{
  $networkId = DFP_NETWORK_ID;
}

// LIST OF TABLES ON CLI
if(@$argv[3]){
  $tableList = array_splice($argv, 3, count($argv) );
}else{
  $tableList = array('LineItem','LineItem_stats',
		     'Order','User','LineItemCreativeAssociation',
		     'Company'
		     );
}

raw( $dw->migrateToWarehouse($tableList, $networkId) );

