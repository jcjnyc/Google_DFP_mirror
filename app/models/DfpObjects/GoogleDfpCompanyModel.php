<?php

  /**
   * GoogleDfpCompanyModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CompanyService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpCompanyModel extends DfpApiModel {

  public $model = array('service' => 'CompanyService',
			'serviceMethods' => array('get' => 'getCompaniesByStatement',
						  'create' => 'createCompany',
						  'update' => 'updateCompany'
						  ),
			'table' => 'Company',
			'cols'  => array( 'id'                       => 'bigint(20) NOT NULL ',
					  'name'                     => 'varchar(255)',
					  'type'                     => 'enum("HOUSE_ADVERTISER","HOUSE_AGENCY","ADVERTISER","AGENCY","AD_NETWORK","AFFILIATE_DISTRIBUTION_PARTNER","CONTENT_PARTNER","UNKNOWN")',
					  'address'                   => 'mediumtext',
					  'email'                     => 'varchar(128)',
					  'faxPhone'                  => 'varchar(63)',
					  'primaryPhone'              => 'varchar(63)',
					  'externalId'                => 'varchar(255)',
					  'comment'                   => 'varchar(1024)',
					  'creditStatus'              => 'enum("ACTIVE","ON_HOLD","CREDIT_STOP","INACTIVE","BLOCKED")',
					  'appliedLabels'             => 'mediumtext',
					  'settings'                  => 'text',
					  'appliedLabels'             => 'text',
					  'primaryContactId'     => 'bigint(20)',
					  'appliedTeamIds'       => 'mediumtext', 
					  'thirdPartyCompanyId'  => 'bigint(20)',
					  'lastModifiedDateTime' => 'datetime' 
					  ),
			'create' => array('name','type','address','email','faxPhone','primaryPhone','externalId','comment','creditStatus','appliedLables'),
			'update' => array(),

			'keys' => array('primary key' => array('id'),
					'index'       => array('name','email','primaryContactId'),
					'fulltext'    => array('appliedTeamIds','appliedLabels')
					),
			'pre_proc' => array('appliedLabels'        => array('DfpUtilityModel', 'isArray'),
					    'appliedTeamIds'       => array('DfpUtilityModel', 'isArray'),
					    'creditStatus'         => array('DfpUtilityModel', 'isEnum'),
					    'settings'             => array('DfpUtilityModel', 'isObject'),
					    'appliedLabels'        => array('DfpUtilityModel', 'isObject'),
					    'enableSameAdvertiserCompetitiveExclusion' => array('DfpUtilityModel','isBool'),
					    'lastModifiedDateTime' => array('DateTimeUtils','ToStringWithTimeZone')
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array('appliedLabels' => 'toArray', 'appliedTeamIds' => 'toArray'),
			'create_skip' => array('appliedLabels', 'thirdPartyCompanyId', 'primaryContactId'),
			'update_skip' => array('appliedLabels', 'thirdPartyCompanyId', 'primaryContactId', 'lastModifiedDateTime')
 			);
  
  
  public function __construct(){
    parent::__construct();
  }
    
  }
