<?php
  /**
   * GoogleDfpLineItemModel Extends DfpApiModel
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/LineItemService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/


class GoogleDfpLineItemModel extends DfpApiModel {
  
  public $model = array('service'  => 'LineItemService',
			'serviceMethods' => array('get' => 'getLineItemsByStatement',
						  'create' => 'createLineItems',
						  'update' => 'updateLineItems'
						  ),
			'table' => 'LineItem',
			'cols'  => array('orderId'       => 'bigint(20)',
					 'id'            => 'bigint(20)',    // read-only
					 'name'          => 'varchar(127)',
					 'externalId'    => 'varchar(63)',
					 'orderName'     => 'varchar(128)',
					 'startDateTime' => 'datetime',
					 'startDateTimeType' => 'enum("USE_START_DATE_TIME","IMMEDIATELY","ONE_HOUR_FROM_NOW")',
					 'endDateTime'   => 'datetime',
					 'autoExtensionDays' => 'int(2)',
					 'unlimitedEndDateTime' => 'int(1)', // isBool
					 'creativeRotationType' => 'enum("EVEN","OPTIMIZED","MANUAL","SEQUENTIAL")',
					 'deliveryRateType'     => 'enum("EVENLY","FRONTLOADED","AS_FAST_AS_POSSIBLE")',
					 'roadblockingType'     => 'enum("ONLY_ONE","ONE_OR_MORE","AS_MANY_AS_POSSIBLE","ALL_ROADBLOCK","CREATIVE_SET")',
					 'frequencyCaps'        => 'text',   // TODO isFrequenceyCap
					 'lineItemType'         => 'enum("SPONSORSHIP","STANDARD","NETWORK","BULK","PRICE_PRIORITY","HOUSE","LEGACY_DFP","CLICK_TRACKING","ADSENSE","AD_EXCHANGE","BUMPER","ADMOB","UNKNOWN")',
					 'priority'             => 'int(2)',
					 'costPerUnit'          => 'bigint', // isMoney
					 'valueCostPerUnit'     => 'bigint', // isMoney
					 'costType'             => 'enum("CPA", "CPC","CPD","CPM","UNKNOWN")',
					 'discountType'         => 'enum("ABSOLUTE_VALUE","PERCENTAGE")',
					 'discount'             => 'bigint(20)',
					 'contractedUnitsBought' => 'bigint(20)',
					 'creativePlaceholders'  => 'text',  // isObject
					 'activityAssociations'  => 'text',  // isObject
					 'targetPlatform'       => 'enum("WEB","MOBILE","ANY")',
					 'environmentType'      => 'enum("BROWSER","VIDEO_PLAYER")',
					 'companionDeliveryOption' => 'enum("OPTIONAL","AT_LEAST_ONE","ALL","UNKNOWN")',
					 'creativePersistenceType'  => 'enum("NOT_PERSISTENT","PERSISTENT_AND_EXCLUDE_NONE","PERSISTENT_AND_EXCLUDE_DISPLAY","PERSISTENT_AND_EXCLUDE_VIDEO","PERSISTENT_AND_EXCLUDE_ALL")',
					 'allowOverbook'                     => 'int(1)', // isBool
					 'skipInventoryCheck'                => 'int(1)', // isBool
					 'skipCrossSellingRuleWarningChecks' => 'int(1)', // isBool
					 'reserveAtCreation'  => 'int(1)',
					 'stats'              => 'varchar(1000)', // isObject
					 'deliveryIndicator'  => 'varchar(100)',  // isObject
					 'deliveryData'       => 'varchar(100)',  // isObject
					 'budget'             => 'bigint(20)', // isMoney
					 'status'             => 'enum("DELIVERY_EXTENDED","DELIVERING","READY","PAUSED","NEEDS_CREATIVES","PAUSED_INVENTORY_RELEASED","PENDING_APPROVAL","COMPLETED","DISAPPROVED","DRAFT","CANCELED")',
					 'reservationStatus'    => 'enum("RESERVED","UNRESERVED")',
					 'isArchived'           => 'int(1)', // isBool
					 'webPropertyCode'     => 'varchar(255)',
					 'appliedLabels'        => 'text',   // isObject
					 'effectiveAppliedLabels' => 'text', // isObject
					 'disableSameAdvertiserCompetitiveExclusion' => 'int(1)', //isBool
					 'lastModifiedByApp'   => 'varchar(255)',
					 'notes'               => 'mediumtext', // fulltext
					 'lastModifiedDateTime' => 'datetime',
					 'creationDateTime'     => 'datetime', 
					 'isPrioritizedPreferredDealsEnabled' => 'int(1)', // isBool
					 'adExchangeAuctionOpeningPriority' => 'int(2)',
					 'customFieldValues'  => 'mediumtext',  // isObject
					 'isSetTopBoxEnabled' => 'int(1)', //isBool
					 'isMissingCreatives' => 'int(1)', //isBool
					 'primaryGoal'        => 'text', // isObject
					 'secondaryGoals'     => 'text', // isObject
					 'grpSettings'        => 'text', // isObject
					 // 'LineItemSummaryType' => 'text', 
					 'targeting'          => 'mediumtext'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array('notes','appliedLabels','effectiveAppliedLabels')
					),
			'pre_proc' => array('startDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'   => array('DateTimeUtils','ToStringWithTimeZone'),
					    'unlimitedEndDateTime' => array('DfpUtilityModel', 'isBool'),
					    'creativeRotationType' => array('DfpUtilityModel', 'isEnum'),
					    'deliveryRateType'     => array('DfpUtilityModel', 'isEnum'),
					    'roadblockingType'     => array('DfpUtilityModel', 'isEnum'),
					    'frequencyCaps'        => array('DfpUtilityModel', 'isObject'),
					    'lineItemType'         => array('DfpUtilityModel', 'isEnum'),
					    'costPerUnit'          => array('DfpUtilityModel', 'isMoney'),
					    'valueCostPerUnit'     => array('DfpUtilityModel', 'isMoney'),
					    'costType'             => array('DfpUtilityModel', 'isEnum'),
					    'discountType'         => array('DfpUtilityModel', 'isEnum'),
					    'creativePlaceholders' => array('DfpUtilityModel', 'isObject'),
					    'allowOverbook'        => array('DfpUtilityModel', 'isBool'),
					    'skipInventoryCheck'   => array('DfpUtilityModel', 'isBool'),
					    'stats'                => array('DfpUtilityModel', 'isObject'),
					    'deliveryIndicator'    => array('DfpUtilityModel', 'isObject'),
					    'deliveryData'         => array('DfpUtilityModel', 'isObject'),
					    'budget'               => array('DfpUtilityModel', 'isMoney'),
					    'status'               => array('DfpUtilityModel', 'isEnum'),
					    'targetPlatform'       => array('DfpUtilityModel', 'isEnum'),
					    'environmentType'      => array('DfpUtilityModel', 'isEnum'),
					    'companionDeliveryOption' => array('DfpUtilityModel', 'isEnum'),
					    'reservationStatus'       => array('DfpUtilityModel', 'isEnum'),
					    'isArchived'              => array('DfpUtilityModel', 'isBool'),
					    'appliedLabels'           => array('DfpUtilityModel', 'isObject'),
					    'effectiveAppliedLabels'  => array('DfpUtilityModel', 'isObject'),
					    'disableSameAdvertiserCompetitiveExclusion' => array('DfpUtilityModel', 'isBool'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'creationDateTime'     => array('DateTimeUtils','ToStringWithTimeZone'),
					    'isPrioritizedPreferredDealsEnabled' => array('DfpUtilityModel', 'isBool'),
					    'customFieldValues'    => array('DfpUtilityModel', 'isObject'),
					    'isMissingCreatives'   => array('DfpUtilityModel', 'isBool'),
					    'primaryGoal'          => array('DfpUtilityModel', 'isObject'),
					    'secondaryGoals'       => array('DfpUtilityModel', 'isObject'),
					    'grpSettings'          => array('DfpUtilityModel', 'isObject'),
					    //'LineItemSummaryType'  => array('DfpUtilityModel', 'isObject'),
					    'targeting'            => array('DfpUtilityModel', 'isObject')
					    ),
			'post_proc' => array('LineItem_stats'         => 'self',
					     'LineItem_frequencyCaps' => 'self',
					     'LineItem_primaryGoal'   => 'self',
					     ),
			'engine'   => 'MyISAM',
			'charset'  => 'utf8',
			'collate'  => 'utf8_unicode_ci',
			);

  public $submodels = array('LineItem_stats' => array('table' => 'LineItem_stats',
						      'cols'  => array('id' => 'bigint(20)',
								       'impressionsDelivered' => 'bigint(20)',
								       'clicksDelivered' => 'bigint(20)',
								       'videoCompletionsDelivered' => 'bigint(20)',
								       'videoStartsDelivered'      => 'bigint(20)'
								       ),
						      'keys'  => array('primary key' => array('id'),
								       'index' => array(),
								       'fulltext' => array()
								       ),
						      'pre_proc' => array(),
						      'post_proc' => array(),
						      'engine'   => 'MyISAM',
						      'charset'  => 'utf8',
						      'collate'  => 'utf8_unicode_ci'						      
						      ),
			    'LineItem_frequencyCaps' => array('table' => 'LineItem_frequencyCaps',
							      'cols'  => array('id' => 'bigint(20)',
									       'maxImpressions' => 'bigint(20)',
									       'numTimeUnits'   => 'bigint(20)',
									       'timeUnit' => 'enum("MINUTE","HOUR","DAY","WEEK","MONTH","LIFETIME","POD")'
									       ),
							      'keys'  => array('primary key' => array('id'),
									       'index' => array(),
									       'fulltext' => array()
									       ),
							      'pre_proc' => array(),
							      'post_proc' => array(),
							      'engine'   => 'MyISAM',
							      'charset'  => 'utf8',
							      'collate'  => 'utf8_unicode_ci'						      
							      ),
			    'LineItem_primaryGoal' => array('table' => 'LineItem_primaryGoal',
							    'cols'  => array('id' => 'bigint(20)',
									     'goalType' => 'varchar(50)',
									     'unitType' => 'varchar(50)',
									     'units' => 'bigint(20)'
									       ),
							      'keys'  => array('primary key' => array('id'),
									       'index' => array('goalType'),
									       'fulltext' => array()
									       ),
							      'pre_proc' => array(),
							      'post_proc' => array(),
							      'engine'   => 'MyISAM',
							      'charset'  => 'utf8',
							      'collate'  => 'utf8_unicode_ci'						      
							      )
			    
			    );
  public function __construct(){
    parent::__construct();
  }

  /** LineItem_stats
   * 
   * build LineItem_stats model in db extrapolating the stats column 
   * 
   **/
  public function LineItem_stats(){
    $i = 0;
    $results = array();

    $this->dbc->createOldTable( $this->submodels['LineItem_stats'] );
    $this->dbc->runQuery( $this->dbc->make_table_create( $this->submodels['LineItem_stats'] ) );
    $resultData = array();    

    //    raw( array_keys( $this->submodels['LineItem_stats']['cols'] ) );
    foreach( $this->dbc->getModelCols($this->model['table'], array('id','stats')) as $row ){
      if(empty($row['stats'])) continue;
      ++$i;
      $statsData = json_decode($row['stats']);
      $resultData[] = array('id' => $row['id'],
			    'impressionsDelivered' => $statsData->impressionsDelivered,
			    'clicksDelivered'      => $statsData->clicksDelivered,
			    'videoCompletionsDelivered' => $statsData->videoCompletionsDelivered,
			    'videoStartsDelivered'      => $statsData->videoStartsDelivered);
      if($i == 100){

	$results[] = $this->dbc->replaceRows( $this->submodels['LineItem_stats']['table'], array_keys( $this->submodels['LineItem_stats']['cols'] ), $resultData);
	$resultData = array();
	$i = 0;
      }
    }
    // ONE MORE TIME TO MAKE SURE WE GET ANY STRAGGLERS
    if($i > 0) $results[] = $this->dbc->replaceRows( $this->submodels['LineItem_stats']['table'], array_keys( $this->submodels['LineItem_stats']['cols']  ), $resultData);
    return($results);    
  }


  /** LineItem_frequencyCaps
   * 
   * build LineItem_frequencyCaps model in db extrapolating the frequencyCaps column 
   * 
   **/
  public function LineItem_frequencyCaps(){
    $i = 0;
    $results = array();

    $this->dbc->createOldTable( $this->submodels['LineItem_frequencyCaps'] );
    $this->dbc->runQuery( $this->dbc->make_table_create( $this->submodels['LineItem_frequencyCaps'] ) );
    $resultData = array();    

    //    raw( array_keys( $this->submodels['LineItem_frequencyCaps']['cols'] ) );
    foreach( $this->dbc->getModelCols($this->model['table'], array('id','frequencyCaps')) as $row ){
      if(empty($row['frequencyCaps'])) continue;

      ++$i;

      // RETURNS AN ARRAY OF OBJECTS 
      $frequencyCapsData = json_decode($row['frequencyCaps']);

      foreach($frequencyCapsData as $fcRow){
	$resultData[] = array('id' => $row['id'],
			      'maxImpressions' => $fcRow->maxImpressions,
			      'numTimeUnits'   => $fcRow->numTimeUnits,
			      'timeUnit'       => $fcRow->timeUnit);
      }

      if($i == 100){
	$results[] = $this->dbc->replaceRows( $this->submodels['LineItem_frequencyCaps']['table'], array_keys( $this->submodels['LineItem_frequencyCaps']['cols'] ), $resultData);
	$resultData = array();
	$i = 0;
      }
    }

    // ONE MORE TIME TO MAKE SURE WE GET ANY STRAGGLERS
    if($i > 0) $results[] = $this->dbc->replaceRows( $this->submodels['LineItem_frequencyCaps']['table'], array_keys( $this->submodels['LineItem_frequencyCaps']['cols']  ), $resultData);
    return($results);    
  }


  /** LineItem_primaryGoal
   * 
   * build LineItem_primaryGoal model in db extrapolating the primaryGoal column 
   * 
   **/
  public function LineItem_primaryGoal(){
    $i = 0;
    $results = array();
      
    $this->dbc->createOldTable( $this->submodels['LineItem_primaryGoal'] );
    $this->dbc->runQuery( $this->dbc->make_table_create( $this->submodels['LineItem_primaryGoal'] ) );
    $resultData = array();    

    //    raw( array_keys( $this->submodels['LineItem_stats']['cols'] ) );
    foreach( $this->dbc->getModelCols($this->model['table'], array('id','primaryGoal')) as $row ){
      if(empty($row['primaryGoal'])) continue;
      ++$i;
      $primaryGoalData = json_decode($row['primaryGoal']);
      $resultData[] = array('id'       => $row['id'],
			    'goalType' => $primaryGoalData->goalType,
			    'unitType' => $primaryGoalData->unitType,
			    'units'    => $primaryGoalData->units
			    );
      if($i == 100){

	$results[] = $this->dbc->replaceRows( $this->submodels['LineItem_primaryGoal']['table'], array_keys( $this->submodels['LineItem_primaryGoal']['cols'] ), $resultData);
	$resultData = array();
	$i = 0;
      }
    }
    // ONE MORE TIME TO MAKE SURE WE GET ANY STRAGGLERS
    if($i > 0) $results[] = $this->dbc->replaceRows( $this->submodels['LineItem_primaryGoal']['table'], array_keys( $this->submodels['LineItem_primaryGoal']['cols']  ), $resultData);
    return($results);    
  }


  }
