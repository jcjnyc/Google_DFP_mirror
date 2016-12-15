<?php
require_once(BASE_DIR.'/vendor/autoload.php');

function __autoload($in){
  $dir_list = array(
				'lib'             	  => '',
				'app/controllers' 	  => 'Controller', 
				'app/models'      	  => '', 
				'app/models/DfpObjects'   => '', 
				'app/models/DfpPql'       => '', 
				'app/models/DfpReports'   => '', 
				'app/views'               => ''
		    );

  // SEARCH PATHS
  // ... so default is Controller 
  // eg) $controller = new Arr();
  //     ... will include app/controllers/ArrController.php
  // - models and views should be called explicitly
  // eg) $model = new ArrModel(); - in file app/models/ArrModel.php
  // eg) $model = new ArrView();  - in file app/views/ArrView.php
  $list = array();
  foreach($dir_list as $dir => $role ){ 
    $list[] = BASE_DIR.'/'.$dir.'/'.$in.$role.'.php';
    if ( file_exists( BASE_DIR.'/'.$dir.'/'.$in.$role.'.php') ){ 
      require_once(BASE_DIR.'/'.$dir.'/'.$in.$role.'.php');
      return;
    }
  }

  error_log("No such file: ".print_r($list,true) );

}




function parseRequest(){
    
  $data = array('controller' => DEFAULT_CONTROLLER,
		'action'     => 'index',
		'data'       => null );

  if( isset( $_GET['in'] )  ){

    if($_GET['in'] == '/index.php'){
      return($data);
    }else{
      $tmp = explode('/',$_GET['in'],4);    
      !empty($tmp[1]) ?  $data['controller']   = ucfirst($tmp[1]) : $data['controller'] = DEFAULT_CONTROLLER;
      !empty($tmp[2]) ?  $data['action']       = $tmp[2] : $data['action']     = 'index';
      !empty($tmp[3]) ?  $data['data']         = $tmp[3] : $data['data']       = null;
    }
  }

  // WE CAN CLEAN AND VALIDATE THIS DATA SOMEHOW
  $data['get']  = $_GET;
  $data['post'] = $_POST;

  return($data);

}


function isValidController( $in ){
  error_log(__METHOD__."( $in )");
  $list = explode(',',VALID_CONTROLLERS); 
  error_log( "$in in list? :".in_array($in, $list) );
  return( in_array($in, $list) );
}

function raw($in=null){
  if(isset($in)){
    if(php_sapi_name() == "cli") {
      error_log( json_encode($in,JSON_PRETTY_PRINT) );
    }else{
      header('CONTENT-TYPE: text/plain');
      echo json_encode($in,JSON_PRETTY_PRINT);
    }
    exit;
  }else{
    var_export($_SERVER);
  }
  exit;
}

spl_autoload_register('__autoload');
