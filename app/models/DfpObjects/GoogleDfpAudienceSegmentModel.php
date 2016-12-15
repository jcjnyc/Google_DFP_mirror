<?php

  /**
   * GoogleDfpAudienceSegmentModel Extends DfpApiModel
   *
   * Operate against the AudienceSegmentService
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/AudienceSegmentService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpAudienceSegmentModel extends DfpApiModel {

  public $model = array('service' => 'AudienceSegmentService',
			'serviceMethods' => array('get'      => 'getAudienceSegmentsByStatement',
						  'create'   => 'createAudienceSegments',
						  'update'   => 'updateAudienceSegments',
						  'action'   => 'performAudienceSegmentAction'
						  ),
			'table' => 'AudienceSegment',
			'cols' => array('id'           => 'bigint(20)',
					'name'         => 'varchar(255)',
					'categoryIds'  => 'text', // isArray
					'description'  => 'varchar(255)',
					'status'       => 'enum("ACTIVE","INACTIVE")',
					'size'         => 'bigint(20)',
					'dataProvider' => 'varchar(255)',
					'type'         => 'enum("FIRST_PARTY","SHARED","THIRD_PARTY","UNKNOWN")',
					'approvalStatus' => 'enum("UNAPPROVED","APPROVED","REJECTED","UNKNOWN")',
					'cost'         => 'bigint(20)',
					'licenseType'  => 'enum("DIRECT_LICENSE","GLOBAL_LICENSE","UNKNOWN")',
					'startDateTime' => 'datetime',
					'endDateTime'   => 'datetime',
					'pageViews'     => 'bigint(20)', 
					'recencyDays'   => 'int(2)',
					'membershipExpirationDays' => 'int(3)',
					'rule'          => 'text',
					//'AudienceSegmentType' => 'varchar(255)' - Depricated in 201502
					),
			'keys' => array('primary key' => array('id'),
					'index'       => array('name', 'description'), 
					'fulltext'    => array()
					),
			'pre_proc' => array('categoryIds'        => array('DfpUtilityModel', 'isArray'),
					    'status'             => array('DfpUtilityModel', 'isEnum'),
					    'type'               => array('DfpUtilityModel', 'isEnum'),
					    'dataProvider'       => array('DfpUtilityModel', 'isObject'),
					    'cost'               => array('DfpUtilityModel', 'isMoney'),
					    'startDateTime'      => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'        => array('DateTimeUtils','ToStringWithTimeZone'),
					    'rule'               => array('DfpUtilityModel', 'isObject'),
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration'  => array('dataProvider' => 'toObject'),   
            'create_skip' => array('type'),
            'update_skip' => array()
			);
  
  public function __construct(){
    parent::__construct();
  }
  
  }