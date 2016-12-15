<?php

  /**
   * GoogleDfpPackageModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/PackageService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpPackageModel extends DfpApiModel {
  
  public $model = array('service'  => 'PackageService',
			'serviceMethods' => array('get' => 'getPackagesByStatement',
						  'create' => 'createPackages',
						  'update' => 'updatePackages'
						  ),
			'table' => 'Package',
			'cols'  => array('id'            => 'bigint(20) NOT NULL',
					 'proposalId'    => 'bigint(20) NOT NULL',
					 'proposalPackageId' => 'bigint(20) NOT NULL',
					 'rateCardId'    => 'bigint(20) NOT NULL',
					 'name'          => 'varchar(255)',
					 'comments'      => 'text',
					 'status'        => 'enum("IN_PROGRESS","COMPLETED","UNKNOWN")',
					 'startDateTime' => 'datetime',
					 'endDateTime'   => 'datetime',
					 'lastModifiedDateTime'   => 'datetime'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('proposalId', 'proposalPackageId', 'name'),
					'fulltext'    => array('comments')
					),
			'pre_proc' => array('status'        => array('DfpUtilityModel', 'isEnum'), 
					    'startDateTime' => array('DateTimeUtils','ToStringWithTimeZone'),
					    'endDateTime'   => array('DateTimeUtils','ToStringWithTimeZone'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci'
			);
			

  public function __construct(){
    parent::__construct();
  }
  

  }
