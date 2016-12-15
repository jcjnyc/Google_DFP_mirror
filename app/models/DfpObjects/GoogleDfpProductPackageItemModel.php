<?php

  /**
   * GoogleDfpProductPackageItemModel Extends DfpApiModel
   *
   *  https://developers.google.com/doubleclick-publishers/docs/reference/v201502/ProductPackageItemService
   *
   * @author james jackson <jamescaseyjackson@gmail.com>
   **/

class GoogleDfpProductPackageItemModel extends DfpApiModel {
  
  public $model = array('service'  => 'ProductPackageItemService',
			'serviceMethods' => array('get' => 'getProductPackageItemsByStatement',
						  'create' => 'createProductPackageItems',
						  'update' => 'updateProductPackageItems'
						  ),
			'table' => 'ProductPackageItem',
			'cols'  => array('id'               => 'bigint(20) NOT NULL',
					 'productId'        => 'bigint(20) NOT NULL',
					 'productPackageId' => 'bigint(20) NOT NULL',
					 'isMandatory'      => 'int(1)',
					 'archivedStatus'   => 'enum("ARCHIVED","NOT_ARCHIVED","PRODUCT_TEMPLATE_ARCHIVED","UNKNOWN")'
					 ),
			'keys' => array('primary key' => array('id'),
					'index'       => array('productId', 'productPackageId','archivedStatus'),
					'fulltext'    => array()
					),
			'pre_proc' => array('isMandatory'        => array('DfpUtilityModel', 'isBool'),
					    'archivedStatus'     => array('DfpUtilityModel', 'isEnum')
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
