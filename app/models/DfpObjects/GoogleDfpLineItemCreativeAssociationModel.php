<?php

  /**
   * GoogleDfpLineItemCreativeAssociationModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/LineItemCreativeAssociationService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpLineItemCreativeAssociationModel extends DfpApiModel {

  public $sort  = 'lineItemId,creativeId';
  public $model = array('service'  => 'LineItemCreativeAssociationService',
			'serviceMethods' => array('get' => 'getLineItemCreativeAssociationsByStatement',
						  'create' => 'createLineItemCreativeAssociations',
						  'update' => 'updateLineItemCreativeAssociations'),
			'table' => 'LineItemCreativeAssociation',
			'cols' => array('lineItemId'                   => 'bigint(20)',
					'creativeId'                   => 'bigint(20)',
					'creativeSetId'                => 'bigint(20)',
					'manualCreativeRotationWeight' => 'bigint(20)',
					'sequentialCreativeRotationIndex'   => 'bigint(20)',
					'startDateTime'        => 'datetime',
					'startDateTimeType'    => 'enum("USE_START_DATE_TIME","IMMEDIATELY","ONE_HOUR_FROM_NOW")',
					'endDateTime'          => 'datetime',
					'destinationUrl'       => 'varchar(2048)',
					'sizes'                => 'text',
					'status'               => 'enum("ACTIVE","NOT_SERVING","INACTIVE","DELETED")',
					'stats'                => 'text',
					'lastModifiedDateTime' => 'datetime',
					'targetingName'        => 'varchar(255)'
					),
			'keys' => array('primary key' => array( 'lineItemId','creativeId'), //,'creativeSetId'), 
					'index'       => array('status',),
					'fulltext'    => array()
					),
			'pre_proc' => array('startDateTimeType' => array('DfpUtilityModel', 'isEnum'),
					    'sizes'  => array('DfpUtilityModel', 'isObject'),
					    'status' => array('DfpUtilityModel', 'isEnum'),
					    'stats'  => array('DfpUtilityModel', 'isObject'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'startDateTime'        => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'          => array('DateTimeUtils','ToStringWithTimeZone')
					    ),
			'post_proc' => array('LineItemCreativeAssociation_stats' => 'self'),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array(),
			'create_skip' => array(),
			'update_skip' => array()
			);

  public $submodels = array('LineItemCreativeAssociation_stats' => array('table' => 'LineItemCreativeAssociation_stats',
									 'cols'  => array('lineItemId'                   => 'bigint(20)',
											  'creativeId'                   => 'bigint(20)',
											  'creativeSetId'                => 'bigint(20)',
											  'impressionsDelivered'         => 'bigint(20)',
											  'clicksDelivered'              => 'bigint(20)',
											  'videoCompletionsDelivered'    => 'bigint(20)',
											  'videoStartsDelivered'         => 'bigint(20)',
											  'creativeSetStats'             => 'text',
											  'costInOrderCurrency'          => 'varchar(100)',
											  ),
									 'keys'  => array('primary key' => array('lineItemId',
														 'creativeId',
														 'creativeSetId'),
											  'index' => array(),
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
  

  /** LineItemCreativeAssociation_stats
   * 
   * build LineItemCreativeAssociation_stats model in db extrapolating the stats column 
   * 
   **/
  public function LineItemCreativeAssociation_stats(){
    $i = 0;
    $results = array();

    $this->dbc->createOldTable( $this->submodels['LineItemCreativeAssociation_stats'] );
    $this->dbc->runQuery( $this->dbc->make_table_create( $this->submodels['LineItemCreativeAssociation_stats'] ) );
    $resultData = array();    

    //    raw( array_keys( $this->submodels['LineItemCreativeAssociation_stats']['cols'] ) );
    foreach( $this->dbc->getModelCols($this->model['table'], array('lineItemId','creativeId','creativeSetId','stats')) as $row ){
      if(empty($row['stats'])) continue;
      ++$i;
      $statsData = json_decode($row['stats']);
      $resultData[] = array('lineItemId' => $row['lineItemId'],
			    'creativeId' => $row['creativeId'] ? $row['creativeId'] : 0,
			    'creativeSetId' => $row['creativeSetId'] ? $row['creativeSetId'] : 0,
			    'impressionsDelivered' => $statsData->stats->impressionsDelivered,
			    'clicksDelivered'      => $statsData->stats->clicksDelivered,
			    'videoCompletionsDelivered' => $statsData->stats->videoCompletionsDelivered,
			    'videoStartsDelivered'      => $statsData->stats->videoStartsDelivered,
			    "creativeSetStats"          => json_encode( $statsData->creativeSetStats),
			    "costInOrderCurrency"       => json_encode( $statsData->costInOrderCurrency) 
			    );
      if($i == 100){

	$results[] = $this->dbc->replaceRows( $this->submodels['LineItemCreativeAssociation_stats']['table'], array_keys( $this->submodels['LineItemCreativeAssociation_stats']['cols'] ), $resultData);
	$resultData = array();
	$i = 0;
      }
    }
    // ONE MORE TIME TO MAKE SURE WE GET ANY STRAGGLERS
    if($i > 0) $results[] = $this->dbc->replaceRows( $this->submodels['LineItemCreativeAssociation_stats']['table'], array_keys( $this->submodels['LineItemCreativeAssociation_stats']['cols']  ), $resultData);
    return($results);    
  }

  }