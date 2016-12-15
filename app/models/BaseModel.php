<?php

/**
 * use Helper
 **/
use lib\Helper as Helper;

/**
 * 
 *
 *
 **/
class BaseModel {

  /** 
  * db connection
  **/
  public $dbc; 

  /** 
  * debug setting 
  **/
  public $debug = 0;


  /** __construct()
   * 
   * sets up the DB connection 
   **/
  public function __construct(){
    $this->dbc = DB::getConnect();
    $this->helper = new Helper();
  }


  /** getSchema()
   *
   * pass thru to $dbc->getSchema() - sometimes we need to be able to set/get schema from cron script
   **/
  public function getSchema(){
    return $this->dbc->getSchema();
  }

  /** setSchema()
   *
   * pass thru to $dbc->setSchema() - sometimes we need to be able to set/get schema from cron script
   *
   **/
  public function setSchema($n){
    return $this->dbc->setSchema($n);
  }

  /**
   * prefixTable 
   *  Prefix each element in a table definition with some "string" + "."  to make it easier to create joins from 
   *  predefined table structures
   *
   * @param string prefix to be added to columns
   * @param array table definition array 
   **/
  public function prefixTable($prefix,$table){
    foreach($table as $item){
      $out[] = $prefix.'.'.$item;
    }
    return $out;
  }


  /**
   * aliasCols
   *  Create an alias for each item in list by using value and prefix
   *  
   *
   * @param string prefix to be added to columns
   * @param array table definition array 
   **/
  public function aliasCols($prefix,$table){
    foreach($table as $item){
      $out[] = $item.' AS '.$prefix.'_'.$item;
    }
    return $out;
  }


  }

