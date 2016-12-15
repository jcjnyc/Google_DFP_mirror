<?php

  /** 
   * Class to interface with Google DFP API 
   * 
   * @author james jackson 
   *
   **/
require_once(BASE_DIR.'/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp/Util/'.GOOGLE_DFP_API_VERSION.'/DateTimeUtils.php');
//require_once(BASE_DIR.'/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp/Util/'.GOOGLE_DFP_API_VERSION.'/ReportUtils.php');
require_once(BASE_DIR.'/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp/Util/'.GOOGLE_DFP_API_VERSION.'/ReportDownloader.php');
require_once(BASE_DIR.'/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Dfp/Util/'.GOOGLE_DFP_API_VERSION.'/StatementBuilder.php');
require_once(BASE_DIR.'/vendor/googleads/googleads-php-lib/examples/Common/ExampleUtils.php');

/**
 * use Helper
 **/
use lib\Helper as Helper;

class GoogleDfpModel extends BaseModel {
  
  public $dbc; 
  
  public $user; 

  public $statement; 

  // INFO ABOUT NETWORKS FOR REPORT GENERATION 
  public $networkService;
  public $networkData; 
  public $allNetworkData; 

  public $debug = null;

  public function __construct($log=null){
    parent::__construct();

    // HELPER METHODS IN lib/Helper.php 
    $this->helper = new Helper;

    // DFP USER CONNECTION 
    $this->user = new DfpUser();

    // LOGGING 
    $log ? $this->user->LogDefault() : $this->user->LogOff(); 

  }

  /**
   * getNetworkData 
   *
   * @return mixed sets this->networkData object 
   **/
  public function getNetworkData(){
    $this->networkService     = $this->user->GetService('NetworkService',  GOOGLE_DFP_API_VERSION);
    $this->networkData =
      $this->networkService->getCurrentNetwork();
  }


  /**
   * getAllNetworkData 
   *
   * @return mixed sets this->allNetworksData object 
   **/
  public function getAllNetworksData(){
    $this->networkService     = $this->user->GetService('NetworkService',  GOOGLE_DFP_API_VERSION);
    $this->allNetworkData =
      $this->networkService->getAllNetworks();
  }





}