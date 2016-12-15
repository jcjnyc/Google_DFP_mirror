<?php

/**
 * GoogleAuthManagerModel - manage the auth.ini files 
 * 
 * List, unzip and generally manage links to Google auth.ini files found in /lib  
 * 
 * 
 * @author james jackson <jamescaseyjackson@gmail.com>
 **/

class GoogleAuthManagerModel extends BaseModel {

  public $vendorPath = '/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp';

  /**
   *
   *
   **/
  public function __construct(){

  }

  /**
   * listAuthFiles - return list of auth.ini files
   * 
   **/
  public function listAuthFiles(){
    return( glob(CONFIG_DIR.'/*auth.ini') );
  }

  /**
   * getActiveAuthFile - show contents of auth ini that is active
   *
   **/
  public function getActiveAuthFile($in=null){
    if($in==null){
      $in=BASE_DIR.'/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp/auth.ini';
    }
    $out['link'] = readlink($in);
    $out['ini']  = parse_ini_file($in);
    return($out);
  }



  /**
   * unzipAuthConfigs - unzip auth files in lib/*
   *
   **/
  public function unzipAuthConfigs(){
    $file = CONFIG_DIR.'/auth_ini_files.zip';
    
    $cmd = 'unzip -o -d '.CONFIG_DIR.' -P '.ZIP_PASSWORD.' '.$file;
    if (file_exists($file)){
      return(`$cmd`);
    }else{
      die('no such file '.$file);
    }
  }

  /**
   * setAuthFile - put the symlink in place for a specific config
   *
   **/
  public function setAuthFile($in){
    if( ! is_file(CONFIG_DIR.'/'.$in.'_auth.ini') ){
      die('missing target auth file: '.$in."_auth.ini\n");
    }
    if( is_file(BASE_DIR.$this->vendorPath.'/auth.ini') ){
      unlink(BASE_DIR.$this->vendorPath.'/auth.ini');
    }
    if(is_dir(BASE_DIR.$this->vendorPath)){
      $cmd = 'ln -fs '.CONFIG_DIR.'/'.$in.'_auth.ini '.BASE_DIR.$this->vendorPath.'/auth.ini';
      return(`$cmd`);
    }
  }




  }