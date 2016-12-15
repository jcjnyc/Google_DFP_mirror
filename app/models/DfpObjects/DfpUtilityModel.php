<?php


  /**
   * GoogleDfpUtilityModel
   * 
   * Tools for dealing with DFP data 
   *
   * @author jamescaseyjackson@gmail.com 
   **/

class DfpUtilityModel {

  public function __construct(){
    
  }

  /** listAllModels()
   * 
   * list completed models from app/models/Google/ directory
   **/
  public static function listAllModels(){
    $files = glob(__DIR__.'/GoogleDfp*Model.php');
    return preg_filter('/.*GoogleDfp(\w+)Model\.php/','$1',$files);
  }


  // UTILITY FUNCTIONS FOR DEALING WITH GOOGLE DFP BOOL (RETURNS 'true'/'false'
  public static function isEnum($in){
    return is_array($in) ? $in[0] : $in;
  }

  // UTILITY FUNCTIONS FOR DEALING WITH GOOGLE DFP BOOL (RETURNS 'true'/'false'
  public static function isBool($in){
    return $in == 'true' ? 1 : 0;
  }
  
  public static function toBool($in){
    return $in == 1 ? "true" : "false";
  }

  // UTILITY FUNCTION FOR DEALING WITH GOOGLE DFP OBJECTS THAT I JUST WANT TO STORE
  public static function isArray($x=array()){
    return json_encode($x,true);
  }
  
   public static function toArray($x=array()){
    return json_decode($x,true);
  }

  // UTILITY FUNCTION FOR DEALING WITH GOOGLE DFP OBJECTS THAT I JUST WANT TO STORE AS JSON OBJECTS
  public static function isObject($x){
    return json_encode($x);
  }
  
  public static function toObject($x) {
    return json_decode($x);
  }

  // UTILITY FUNCTION TO TURN SIZE TO STRING 
  // - https://developers.google.com/doubleclick-publishers/docs/reference/v201502/InventoryService.Size
  public static function isSize($x){
    return $x->width.'x'.$x->height.'::'.DfpUtilityModel::isBool($x->isAspectRatio);
  }

  // UTILITY FUNCTION TO TURN MONEY TO STRING 
  // - https://developers.google.com/doubleclick-publishers/docs/reference/v201502/LineItemService.Money
  public static function isMoney($x){
    return $x->microAmount;
  }
  
  public static function toMoney($x) {
      $value = new Money('USD', $x);
      return $value;
  }
  
  public static function isNull($x) {
      return NULL;
  }
    
  /** columnNullCount()
   *
   * foreach column of a model check if it is null
   * 
   **/
  public static function columnNotNullCount($model){
    $dbc = DB::getConnect();
    print 'DB.Model: '.$dbc->getSchema().'.'.$model['table']."\n";
    foreach($model['cols'] as $col => $def){
      $sql = 'select count(*) as num from '.$model['table'].' where '.$col.' is not null';
      $out = $dbc->runQuery($sql);
      print $col."\t".$out[0]['num']."\n";
    }
  }


  }