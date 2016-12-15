<?php
  /**
   * importDfpReport
   *
   *  create simple(ish) interface to running DFP reports from CLI
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   *
   **/

require_once(dirname(__DIR__).'/config/config.php');
require_once(dirname(__DIR__).'/lib/bootstrap.php');

// REMOVE SCRIPT NAME SO WE HAVE JUST A FLAG LIST
$cmd = array_shift($argv);

$file = $table = $debug = null;

// PARSE OUT THE CLI ARGS 
foreach($argv as $item){
  if( !preg_match('/=/',$item) || !preg_match('/^--/',$item) ){
    help($argv);
  }
  
  $flagVal = array();
  $flagVal = explode('=',$item,2);

  if($flagVal[0] == '--file'){
    $file = trim($flagVal[1]);
  }elseif($flagVal[0] == '--report'){
    $report = trim($flagVal[1]);
  }elseif($flagVal[0] == '--debug'){
    $debug = trim($flagVal[1]);
  }else{
    help($argv);
  }
}




// EXECUTE THE REPORT QUERY AND WRITE REPORT TO files/ DIR
$obj = $report.'Model';
$gdfp = new $obj();

if($debug) $gdfp->debug = $debug;

$gdfp->reportFile = BASE_DIR.FILES_DIR.'/'.basename($file);

if(! file_exists( $gdfp->reportFile ) ){ 
  print "NO REPORT FILE: ".$gdfp->reportFile." ?? \n";
  exit;
}else{
  print "USING REPORT FILE: ".$gdfp->reportFile."\n";
}

// LOAD THE REPORT IF DEFINED
if( !empty($report) ) print "LINES IMPORTED TO ".$gdfp->model['table'].": ".$gdfp->importReportFile()."\n\n";

exit;
////////////////////////////////////////////////////////

function help($in){
  print "HELP!!!
 All args MUST be in the format '--flag=one:or_more:values:here' 

   --report - name of the report to run, triggers import into DB 
   --format - define the output file format 
              file formats are CSV_DUMP, CSV_EXCEL, XML
              the three TRUE/FALSE bools are for
                - includeReportProperties
                - includeTotalsRow 
                - useGzipCompression

              DEFAULT:  CSV_DUMP:TRUE:TRUE:FALSE 

   --json - path to json file that defines report parameters
   --label - what to name the report file output file that will be in files/ dir
   --dateType - date range configuration string 
                format is DATE_TYPE:startDate:endDate 
                startDate and endDate are only used if DATE_TYPE is CUSTOM_DATE
                
                DEFAULT: YESTERDAY 
                MORE INFO: https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ReportService.ReportQuery#dateRangeType
   --debug - turn on debug output to STDERR 
                Example: --debug=on


 Full Example:
   php cron/importDfpReport.php --format=CSV_DUMP:TRUE:TRUE:FALSE  --json=reportTemplates/viewability_report.json --label=VISIBILITY --dateType=LAST_MONTH

";
  print_r($in);
  exit;
}


////////////////////////////////////////////////////////////////
/**

$gdfp->reportQueryData['dateRangeType'] = array('YESTERDAY');
//$gdfp->reportQueryData['startDate'] = array( 2015, 1, 1);
//$gdfp->reportQueryData['endDate']   = array( 2015, 1, 2);


$gdfp->reportQueryData['dimensions']    = array('AD_UNIT_NAME',
						'ORDER_NAME', 
						'LINE_ITEM_NAME',
						'LINE_ITEM_TYPE',
						//'DEVICE_CATEGORY_NAME'
						);


$gdfp->reportQueryData['adUnitView']    = array('TOP_LEVEL');

$gdfp->reportQueryData['columns']       = array('AD_SERVER_IMPRESSIONS',
						'AD_SERVER_CLICKS', 
						'AD_SERVER_CTR',
						//
						'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS',
						'AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS',
						'AD_EXCHANGE_LINE_ITEM_LEVEL_CTR',
						//
						'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS',
						'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS',
						'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE',
						'TOTAL_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS',
						'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE',
						//
						'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS',
						'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS',
						'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE',
						'AD_SERVER_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS',
						'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE',
						//
						'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS',
						'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS',
						'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE',
						'AD_EXCHANGE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS',
						'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE',
						

						);

$gdfp->reportQueryData['dimensionAttributes']       = array('AD_UNIT_CODE',
							    'ORDER_AGENCY',
							    'ORDER_START_DATE_TIME',
							    'ORDER_END_DATE_TIME',				    
							    'LINE_ITEM_START_DATE_TIME',
							    'LINE_ITEM_END_DATE_TIME',
							    'LINE_ITEM_GOAL_QUANTITY',
							    'LINE_ITEM_SPONSORSHIP_GOAL_PERCENTAGE',
							    'LINE_ITEM_LIFETIME_IMPRESSIONS',
							    'LINE_ITEM_LIFETIME_CLICKS',
							    'LINE_ITEM_PRIORITY',
							    'LINE_ITEM_CONTRACTED_QUANTITY',
							    'LINE_ITEM_COST_PER_UNIT',
							    //'LINE_ITEM_CURRENCY_CODE'
							    
							    );
**/


