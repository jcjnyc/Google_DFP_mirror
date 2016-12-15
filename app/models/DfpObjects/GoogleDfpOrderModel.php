<?php

  /**
   * GoogleDfpOrderModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/OrderService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpOrderModel extends DfpApiModel {
  
  public $model = array('service'  => 'OrderService',
			'serviceMethods' => array('get' => 'getOrdersByStatement',
						  'create' => 'createOrders',
						  'update' => 'updateOrders'
						  ),
			'table' => 'Order',
			'cols'  => array('id'            => 'bigint(20) NOT NULL',
					 'name'          => 'varchar(255)',
					 'startDateTime' => 'datetime',
					 'endDateTime'   => 'datetime',
					 'unlimitedEndDateTime' => 'int(1)',
					 'status'        => 'enum("DRAFT","PENDING_APPROVAL","APPROVED","DISAPPROVED","PAUSED","CANCELED","DELETED","UNKNOWN")',
					 'isArchived'      => 'int(1)',
					 'notes'           => 'text',
					 'externalOrderId' => 'bigint(20)', 
					 'poNumber'        => 'varchar(255)',
					 'currencyCode'    => 'varchar(10)', 
					 'advertiserId'    => 'bigint(20)',
					 'advertiserContactIds' => 'text',
					 'agencyId'             => 'bigint(20)',
					 'agencyContactIds'     => 'text',
					 'creatorId'            => 'bigint(20)',
					 'traffickerId'         => 'bigint(20)',
					 'secondaryTraffickerIds'    => 'text', 
					 'salespersonId'             => 'bigint(20)',
					 'secondarySalespersonIds'   => 'text', 
					 'totalImpressionsDelivered' => 'bigint(20)',
					 'totalClicksDelivered'   => 'bigint(20)',
					 'totalBudget'            => 'varchar(255)',
					 'appliedLabels'          => 'text',
					 'effectiveAppliedLabels' => 'text',
					 'lastModifiedByApp'      => 'varchar(255)',
					 'isProgrammatic'         => 'int(1)',
					 'programmaticSettings'   => 'text', 
					 'appliedTeamIds'         => 'text',
					 'lastModifiedDateTime'   => 'datetime', 
					 'customFieldValues'      => 'text'
					 
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name'),
					'fulltext'    => array()
					),
			'pre_proc' => array('startDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'   => array('DateTimeUtils','ToStringWithTimeZone'),
					    'unlimitedEndDateTime' => array('DfpUtilityModel', 'isBool'),
					    'isArchived'           => array('DfpUtilityModel', 'isBool'),
					    'advertiserContactIds' => array('DfpUtilityModel', 'isArray'),
					    'agencyContactIds'     => array('DfpUtilityModel', 'isArray'),
					    'secondaryTraffickerIds'  => array('DfpUtilityModel', 'isArray'),
					    'secondarySalespersonIds' => array('DfpUtilityModel', 'isArray'),
					    'totalBudget'            => array('DfpUtilityModel', 'isMoney'),  // SHOULD CHECK FOR MONEY TO STRING 
					    'appliedLabels'          => array('DfpUtilityModel', 'isArray'),
					    'effectiveAppliedLabels' => array('DfpUtilityModel', 'isArray'),
					    'isProgrammatic'       => array('DfpUtilityModel', 'isBool'),
					    'programmaticSettings' => array('DfpUtilityModel', 'isArray'),
					    'appliedTeamIds'       => array('DfpUtilityModel', 'isArray'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'customFieldValues'    => array('DfpUtilityModel', 'isArray')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('secondarySalespersonIds' => 'toArray', 'secondaryTraffickerIds' => 'toArray', 'customFieldValues' => 'toArray',
                                 'programmaticSettings' => 'toArray', 'appliedLabels' => 'toArray', 'effectiveAppliedLabels' => 'toArray',
                                 'customFieldValues' => 'toArray'),  
			'create_skip' => array('customFieldValues', 'appliedTeamIds'),
			'update_skip' => array('appliedTeamIds', 'customFieldValues')		
			);
			

  public function __construct(){
    parent::__construct();
  }
  

  }
