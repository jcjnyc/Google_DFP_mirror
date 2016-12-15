<?php 
  /**
   * Interface to reports for DFP 
   *
   **/

  /**
   * GoogleDfpReportModel 
   *
   * Define a reportQueryData object and call runReport() optionally you may pass 
   *   in name to constructor to label output file, which will be GZIP'd CSV in files/ dir
   *
   *
   *
   **/
class GoogleDfpReportModel extends GoogleDfpModel {

  /**
   * set in GoogleDfpModel - report generation service endpoint
   **/
  public $reportService;

  /**
   * set in GoogleDfpModel - retreive network id
   **/
  public $networkService;

  /**
   * data from network service endpoint 
   **/
  public $networkData;

  /** 
   * label your report output file - present format is networkId__$fileLabel__reportId.csv.gz
   **/
  public $fileLabel = 'TestReport';

  /** 
   * ID of a running report 
   **/
  public $reportJob;

  /**
   * report query object 
   **/
  public $reportQuery;

  /** 
   * reportDownloader
   **/
  public $reportDownloader;

  /** 
   * format of download file options 
   **/
  public $fileFormatOptions;


  /**
   * BATCH SIZE FOR DB INPUT
   **/
  const BATCH_ROWS = 1000;


  /**
   * reportQueryData - where the report params are listed
   *
   *  v201502 - check carefully that you are using same version of API
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery#adUnitView
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery#customFieldIds
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery#statement
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery#dateRangeType
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.Dimension
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.DimensionFilter
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.Column
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.DimensionAttribute
   *  
   *
   **/
  public $reportQueryData = array ('dimensions' => array(),
				   'adUnitView' => array(),
				   'columns'    => array(),
				   'contentMetadataKeyHierarchyCustomTargetingKeyIds' => array(),
				   'dimensionAttributes' => array(),
				   'dateRangeType' => array(),
				   'startDate' => array(),
				   'endDate'   => array(),
				   'dimensionFilters' => array(),
				   'statement' => array(),
				   'timezone' => array()
				   );


  /**
   * output file for in FILES_DIR 
   **/ 
  public $reportFile; 


  /**
   * output format options
   **/ 
  public $reportFormat;

  /**
   * debug - debugging output 
   *   0 no debugging output 
   *   1 outputs status info
   *   2 outputs data and doesn't actually write to DB or file 
   **/
  public $debug = 0;


  /**
   * constructor
   * 
   * constructor takes two args to define output file data 
   * 
   * @param string file label - name for the report file 
   * @param array file format definition 
   **/
  public function __construct($in=null, $format = array()){
    if(!empty($in)) $this->fileLabel = $in; 
    parent::__construct();

    $this->reportService = $this->user->GetService('ReportService', 
						   GOOGLE_DFP_API_VERSION);

    // FILE FORMAT AND FILE SUFFIX 
    //  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportDownloadOptions
    $this->reportFormat = new ReportDownloadOptions();
    $this->reportFormat->exportFormat            = @$format[0] ? $format[0] : 'CSV_DUMP';
    $this->reportFormat->includeReportProperties = @$format[1] == 'TRUE' ? TRUE : FALSE;
    $this->reportFormat->includeTotalsRow        = @$format[2] == 'TRUE' ? TRUE : FALSE;
    $this->reportFormat->useGzipCompression      = @$format[3] == 'TRUE' ? TRUE : FALSE;

  }

  // PLACEHOLDER DO THINGS BEFORE REPORT IS RUN - SHOULD BE IN REPORT MODEL 
  public function reportPreProcess(){
  }

  // PLACEHOLDER TO DO THINGS AFTER REPORT IS RUN - SHOULD BE IN REPORT MODEL 
  public function reportPostProcess(){
  }

  /**
   * runReport 
   * 
   *  execute a report from params in reportQueryData
   *  
   **/
  public function runReport(){

    // DOES NOTHING UNLESS BUILT IN MODEL 
    $this->reportPreProcess();

    // GET'S THE NETWORK STRUCTURE
    $this->getNetworkData();

    // Create report job and query objects.
    $this->reportJob   = new ReportJob();
    $this->reportQuery = new ReportQuery();

    // FILE FORMAT DEBUG 
    if( $this->debug > 0 ) error_log( "FILE FORMATTING: \n". print_r($this->reportFormat, true)."\n" );

    // ------------------------------------------------------------
    // ITERATE THROUGH $this->reportQueryData AND BUILD THE QUERY 
    // ------------------------------------------------------------
    foreach( $this->reportQueryData as $dataType => $valList ){
      if ( empty( $valList ) ) continue; // SKIP ANY EMPTY DATATYPES
      if( $dataType == 'statement' ){     
	$this->reportQuery->{$dataType} = $this->processStatement($valList);
      }elseif( $dataType == 'dateRangeType' || $dataType == 'adUnitView' ){
	$this->reportQuery->{$dataType} = array_pop($valList);
	if($dataType == 'dateRangeType' && $this->reportQuery->{$dataType} != 'CUSTOM_DATE' ){
	  // ADD DATE TYPE AND TODAY'S DATE TO THE FILE LABEL 
	  $this->fileLabel .= '_'.$this->reportQuery->{$dataType}.'-'.date('Ymd',time());
	}

      }elseif( ( $dataType == 'startDate' ) || ( $dataType == 'endDate' ) ){
	// ADD DATE TO THE FILE LABLE IF THERE IS A DATE RANGE 
	$this->fileLabel .= '_'.sprintf('%4d%02d%02d', $valList[0], $valList[1], $valList[2] );
	$this->reportQuery->{$dataType} = new Date ( $valList[0], $valList[1], $valList[2] );

      }else{
	// ADD DATE TO THE FILE LABLE IF THERE IS A DATE RANGE 
	$this->reportQuery->{$dataType} = $valList;
      }
    }

    // HOLDS THE REPORT QUERY AND A LITTLE EXTRA DATA 
    $this->reportJob->reportQuery = $this->reportQuery;

    // PASS IN REPORT JOB AND UPDATE REPORT JOB WITH OUTPUT 
    $this->reportJob = $this->reportService->runReportJob( $this->reportJob );
    if( $this->debug > 0 ) error_log( "NOW RUNNING: \n". print_r($this->reportJob, true)."\n\n" );


    // WAIT FOR THE REPORT TO DOWNLOAD 
    $this->reportDownloader = new ReportDownloader( $this->reportService, $this->reportJob->id );
    $this->reportDownloader->waitForReportReady();


    // SET REPORT FILE -- ADD SUFFIX LATER 
    $this->reportFile = FILES_DIR.'/'.$this->networkData->networkCode.'__'.$this->fileLabel.'__'.$this->reportJob->id;
    $suffix = '.' . strtolower(preg_replace('/_.*/','',$this->reportFormat->exportFormat));
    if( $this->reportFormat->useGzipCompression ) $suffix .=  '.gz';
    $this->reportFile .= $suffix;

    $url = $this->reportService->getReportDownloadUrlWithOptions($this->reportJob->id, $this->reportFormat);
    $ch = curl_init($url);
    $fp = fopen($this->reportFile, "w+");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    // DOES NOTHING UNLESS BUILT IN MODEL 
    $this->reportPostProcess();
  }

  /**
   * processStatement
   *
   *  construt the where statment filter from a json input statement 
   *      "statement": {
   *                   "COUNTRY_CRITERIA_ID = :CID ": { "CID": 2840 } 
   *                   },
   *
   * Note that Numerics should be unquoted values.. 
   *
   * @param mixed json object with statement string as key and jason object as value. 
   *              this is then parsed to K/V pairs and bound
   * @return string where filter string as statement 
   **/
  private function processStatement($in){
    $statementBuilder = new StatementBuilder();
    // UGLY TRICK TO GET IT TO AN EASY TO LOOP STRUCTURE 
    $x = json_decode(json_encode($in), true); 
    foreach( $x as $stub => $keyValList ){
      foreach( $keyValList as $key => $val ){
	$statementBuilder->Where( $stub )->WithBindVariableValue($key, $val);
      }
    }
    return( $statementBuilder->ToStatement() );
  }

  /**
   * importReportFile
   *
   * import a GoogleDfp report file requires that the format be CSV_DUMP with no total row and no report meta data and not gzip'd
   *  set $this->debug=2 and it outputs just data rows and column lists 
   * 
   * @return int number of rows added to table 
   **/
  public function importReportFile(){
    $lc = 0;
    $aRow = array();    
    $rowData = array();  

    // IMPORT REPORT 
    $fh  = fopen( $this->reportFile, "r+");
    while( $line = fgets($fh) ){
      ++$lc;      
      $aRow = array();
      // SKIP FIRST LINE 
      if ($lc == 1){
	continue;
      }

      // PARSE THE REPORT LINE, THIS SHOULD DO ANY CLEANUP OF DATA PER ROW...
      $line = trim($line);
      $aRow = $this->parseReportLine( array_keys($this->model['cols']), $line);
      $rowData[] = $aRow; 

      // DEBUG OUTPUT TO TERM
      if ( $this->debug > 1 ){
	print "model cols: ".count(array_keys($this->model['cols']) )." -- ".json_encode( array_keys($this->model['cols']) )."\n";
	print "data  cols: ".count($aRow)." -- ".json_encode( $aRow )."\n";
	continue;
      }


      // IN BATCHES OF 1000
      if( ($lc % self::BATCH_ROWS) == 0){
	$this->dbc->replaceRows( $this->model['table'], array_keys( $this->model['cols'] ), $rowData);	
	if ( $this->debug > 0 ) print "INSERTED TO: ".$this->model['table']." ROWS: ".count($rowData)."\n";
	$rowData = array();
      }
    }
    fclose($fh);

    
    // CATCH ANY LEFT OVER LINES 
    if(count($rowData) > 0){

      if ( $this->debug > 1 ){
	foreach($rowData as $aRow){
	  print "MODEL COLS: ".count(array_keys($this->model['cols']) )." -- ".json_encode( array_keys($this->model['cols']) )."\n";
	  print "DATA  COLS: ".count($aRow)." -- ".json_encode( $aRow )."\n";
	  continue;
	}
	return;
      }

      $this->dbc->replaceRows( $this->model['table'], array_keys( $this->model['cols'] ), $rowData);	
      if ( $this->debug > 0 ) print "INSERTED TO: ".$this->model['table']." ROWS: ".count($rowData)."\n";
      $rowData = array();
    }

    // RETURN THE NUMBER OF ROWS PROCESSED
    return( $lc - 1) ;
  }
    
  /**
   * parseReportLine
   *  
   *  parse the string and match it to a column set
   *  you should create your own if you need to re arrange report data for table
   *
   *  @param array column list
   *  @param string comma separated row data
   *  @return array associative array of col1=>val,col2=>val
   **/
  protected function parseReportLine($colList, $row){
    $rowData = explode(',',$row);
    foreach($colList as $col){
      $ret[$col] = array_shift($rowData);
    }
    return( $ret );
  }
		    
    
    
    
    
  
  
  /** 
   * testRunReport
   *
   *  build simple report query for testing of connectivity
   *
   **/
  public function testRunReport(){

    /** BULID THE LIST OF REPORT PARAMS **/
    $this->reportQueryData['dimensions'] = array('AD_UNIT_NAME', 'LINE_ITEM_NAME', 'LINE_ITEM_TYPE', 'ORDER_NAME' );
    $this->reportQueryData['adUnitView'] = array('TOP_LEVEL');
    $this->reportQueryData['columns']    = array('AD_SERVER_IMPRESSIONS', 'AD_SERVER_CLICKS', 'AD_SERVER_CTR', 
						 'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS', 'AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS'
						 );
    $this->reoprtQueryData['contentMetadataKeyHierarchyCustomTargetingKeyIds'] = array();
    $this->reportQueryData['dimensionAttributes'] = array('LINE_ITEM_PRIORITY','LINE_ITEM_START_DATE_TIME', 'LINE_ITEM_END_DATE_TIME', 
							  'LINE_ITEM_CONTRACTED_QUANTITY', 'LINE_ITEM_TOTAL_IMPRESSIONS_DELIVERED', 
							  'LINE_ITEM_TOTAL_CLICKS_DELIVERED');
    $this->reportQueryData['startDate']       = array(); // only valid if dateRangeType is 'CUSTOM_DATE'
    $this->reportQueryData['endDate']         = array(); // only valid if dateRangeType is 'CUSTOM_DATE'
    $this->reportQueryData['dateRangeType']   = array('YESTERDAY');
    $this->reportQueryData['dimensionFilters'] = array(); 
    // https://developers.google.com/doubleclick-publishers/docs/reference/v201408/ReportService.Statement
    $this->reportQueryData['statement']       = array( "LINE_ITEM_NAME LIKE ':nameMatch%' " => array('nameMatch' => 'TEST') );
    $this->reportQueryData['timeZone']        = array(); // DEFAULTS TO THE NETWORK TIMEZONE SETTTING 

    /** EXECUTE THE runReport() **/
    $this->runReport();

    exit; 

  }



}