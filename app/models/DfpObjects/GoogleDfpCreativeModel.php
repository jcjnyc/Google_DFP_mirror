<?php

  /**
   * GoogleDfpCreativeModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/CreativeService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

require_once 'Google/Api/Ads/Common/Util/MediaUtils.php';   


class GoogleDfpCreativeModel extends DfpApiModel {
  
  public $model = array('service' => 'CreativeService',
			'serviceMethods' => array('get'    => 'getCreativesByStatement',
						  'create' => 'createCreative',
						  'update' => 'updateCreative'
						  ),
			'table' => 'Creative',
			'cols'  => array('advertiserId'  => 'bigint(20) NOT NULL',
					 'id'            => 'bigint(20) NOT NULL',
					 'name'                 => 'varchar(255)',
					 'size'                 => 'varchar(255)',
					 'previewUrl'           => 'varchar(2048)',
					 'policyViolations'     => 'enum("MALWARE_IN_CREATIVE","MALWARE_IN_LANDING_PAGE","LEGALLY_BLOCKED_REDIRECT_URL","MISREPRESENTATION_OF_PRODUCT","SELF_CLICKING_CREATIVE","GAMING_GOOGLE_NETWORK","DYNAMIC_DNS","PHISHING","UNKNOWN")',
					 'appliedLabels'        => 'text',
					 'lastModifiedDateTime' => 'datetime',
					 'customFieldValues'    => 'text', 
					 'htmlSnippet'          => 'text', 
					 'additionalParameters' => 'text',
					 'publisherId'          => 'text',
					 'lockedOrientation'    => 'enum("UNKNOWN","FREE_ORIENTATION","PORTRAIT_ONLY","LANDSCAPE_ONLY")',
					 'codeSnippet'          => 'text',
					 'isNativeEligible'     => 'int(1)',
					 'studioCreativeId'     => 'bigint(20)',
					 'creativeFormat'       => 'enum("IN_PAGE","EXPANDING","IM_EXPANDING","FLOATING","PEEL_DOWN","IN_PAGE_WITH_FLOATING","FLASH_IN_FLASH","FLASH_IN_FLASH_EXPANDING","IN_APP","UNKNOWN")',
					 'artworkType'          => 'enum("FLASH","HTML5","MIXED")',
					 'totalFileSize'        => 'bigint(20)',
					 'adTagKeys'            => 'text',
					 'customKeyValues'      => 'text',
					 'surveyUrl'            => 'text',
					 'allImpressionsUrl'    => 'text',
					 'richMediaImpressionsUrl' => 'text',
					 'backupImageImpressionsUrl' => 'text',
					 'overrideCss'          => 'text',
					 'requiredFlashPluginVersion' => 'text',
					 'duration'             => 'text',
					 'billingAttribute'     => 'enum("IN_PAGE","FLOATING_EXPANDING","VIDEO","FLASH_IN_FLASH")',
					 'richMediaStudioChildAssetProperties' => 'text',
					 'sslScanResult'        => 'enum("UNKNOWN","UNSCANNED","SCANNED_SSL","SCANNED_NON_SSL")',
					 'sslManualOverride'    => 'enum("UNKNOWN","NO_OVERRIDE","SSL_COMPATIBLE","NOT_SSL_COMPATIBLE")',
					 'clickTrackingUrl'     => 'text',
					 'destinationUrl'       => 'text',
					 'destinationUrlType'   => 'enum("UNKNOWN","CLICK_TO_WEB","CLICK_TO_APP","CLICK_TO_CALL")',
					 'assetSize'            => 'text', 
					 'internalRedirectUrl'  => 'text',
					 'overrideSize'         => 'int(1)',
					 'creativeTemplateVariableValues' => 'text',
					 'snippet'              => 'text', 
					 'expandedSnippet'      => 'text', 
					 'unsupportedCreativeType' => 'text', 
					 'vastXmlUrl'           => 'text',
					 'vastRedirectType'     => 'enum("LINEAR","NON_LINEAR","LINEAR_AND_NON_LINEAR")',
					 'companionCreativeIds' => 'text',
					 'trackingUrls'         => 'text',
					 'vastPreviewUrl'       => 'text'
					 // 'CreativeType'         => 'varchar(255)', //  DEPRICATED in 201502
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('advertiserId','name'), // ,'CreativeType'),
					'fulltext'    => array('codeSnippet')
					),
			'pre_proc' => array('appliedLabels'        => array('DfpUtilityModel', 'isObject'),
					    'customFieldValues'    => array('DfpUtilityModel', 'isArray'),
					    'size'                 => array('DfpUtilityModel', 'isSize'),
					    'lastModifiedDateTime' => array('DateTimeUtils',   'ToStringWithTimeZone'),
					    'lockedOrientation'    => array('DfpUtilityModel', 'isEnum'),
					    'isNativeEligible'     => array('DfpUtilityModel', 'isBool'),
					    'creativeFormat'       => array('DfpUtilityModel', 'isEnum'),
					    'artworkType'          => array('DfpUtilityModel', 'isEnum'),
					    'billingAttribute'     => array('DfpUtilityModel', 'isEnum'),
					    'sslScanResult'        => array('DfpUtilityModel', 'isEnum'),
					    'sslManualOverride'    => array('DfpUtilityModel', 'isEnum'),
					    'destinationUrlType'   => array('DfpUtilityModel', 'isEnum'),
					    'assetSize'            => array('DfpUtilityModel', 'isObject'),
					    'overrideSize'         => array('DfpUtilityModel', 'isBool'),
					    'isInterstitial'       => array('DfpUtilityModel', 'isBool'),
					    'creativeTemplateVariableValues' => array('DfpUtilityModel', 'isObject'),
					    'vastRedirectType'     => array('DfpUtilityModel', 'isEnum'),
					    'companionCreativeIds' => array('DfpUtilityModel', 'isArray'),
					    'trackingUrls'         => array('DfpUtilityModel', 'isArray'),
					    
					    ),
			'post_proc' => array(),
			'engine'    => 'MyISAM',
			'charset'   => 'utf8',
			'collate'   => 'utf8_unicode_ci',
			'migration' => array(),
			'create_skip' => array(),
			'update_skip' => array()			
			);
			

  public function __construct(){
    parent::__construct();
  }

}
