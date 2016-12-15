<?php
/**
 *  Class Helper, should contains all utility methods 
 *
 *  @package lib 
 *  @author Alexander Todorov <some81@gmail.com>
 *  @author james jackson <jamescaseyjackson@gmail.com>
 *  @version 1.0.0
 */
namespace lib;

use Classes\PHPExcel as PHPExcel;
use Aws\S3\S3Client AS S3Client;

class Helper {
  
  public function __construct(){}
  
  public function validateDate($date, $format = 'Y-m-d H:i')
  {
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
  }
  
  // convert microdollar to real money value
  public function microToMoney($in){
    return '$'.number_format(($in * .000001), 2);
  }
  
  public function moneyFormat($in){
    setlocale(LC_MONETARY, 'en_US');
    return money_format('%.2n', $in);
  }
	
	public function impsFormat($number)
	{
		return number_format($number, 0,'.',',');
	}
	
	public function percentFormat($number)
	{
		return $number.'%';
	}
  
  /* dateRange
   * 
   * take in two dates, and an optional step and format and return list of dates between them
   * 
   * @param string date 
   * @param string date
   * @param string optional - step format for date strtotime() function 
   * @param string optional - format of date
   */
  public function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d' ) { 
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    
    while( $current <= $last ) { 
      
      $dates[] = date($format, $current);
      $current = strtotime($step, $current);
    }
    
    return $dates;
  }
  
  /* arrayToCsv
   *  convert an AoA to a CSV w/ optional header array
   *
   * @param array array of arrays
   * @param array simple array with values for a header row
   */
  public function arrayToCsv($data, $headerRow = null){
    $buffer = fopen('php://temp', 'r+');
    $csv = '';
    // WRITE A HEADER TO BUFFER
    if ( is_array($headerRow) ) fputcsv($buffer, $headerRow);
    
    // WRITE DATA
    foreach($data as $row){
      fputcsv($buffer, $row);
    }
    rewind($buffer);
    while( ($tmp = fgets($buffer)) !== false  ){
      $csv .= $tmp;
    }
    fclose($buffer);
    return($csv);
  }
  
  /* sendEmail
   *  send email using PHPMailer and sendmail 
   *  https://github.com/PHPMailer/PHPMailer
   */
  public function sendEmail($to, $from, $subject, $body, $attachments = null, $html = false){

      $mail = new \PHPMailer();
      $mail->isSMTP();
      $mail->isHTML( $html );
      $mail->Host = SMTP_HOST;
      $mail->SMTPAuth = true;
      $mail->Username = SMTP_USER;
      $mail->Password = SMTP_PASS;
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;
      
      // ACCEPT TO ADDRESSES AS SIMPLE STRING, COMMA SEPARATED STRING, SPACE SEPARATED STRING OR ARRAY 
      if(is_array($to)){
	foreach($to as $addr){
	  $addr = trim($addr);
	  if ($this->isValidEmail($addr) ) $mail->AddAddress($addr);
	}
      } else if ( preg_match('/\,/', trim($to) ) ){
	$to = trim($to);
	foreach(explode(',', $to) as $addr){
	  $addr = trim($addr);
	  if ($this->isValidEmail($addr) ) $mail->AddAddress($addr);
	}
      } else if ( preg_match('/ /', trim($to) ) ){
	$to = trim($to);
	foreach(explode(' ', $to) as $addr){
	  $addr = trim($addr);
	  if ($this->isValidEmail($addr) ) $mail->AddAddress($addr);
	}
      } else if ($this->isValidEmail($to)){
	$addr = $to;
	$mail->AddAddress($addr);
      }
      
      $mail->From = $from;
      $mail->FromName = 'monitorAndReport';
      $mail->Subject = $subject;
      $mail->Body = $body;
      
      // ADD ATTACHMENTS IF THERE ARE ANY
      if(is_array($attachments)){
	foreach($attachments as $file){
	  $mail->addAttachment($file);
	}
      }
      //raw($mail);
      if ( ! $mail->Send() ){
	echo "Mailer Error: " . $mail->ErrorInfo;
	raw($mail);
      } 
    }        

     



	// RETURN TRUE/FALSE IF EMAIL ADDRESS IS SYNTACTICALLY VALID
	public function isValidEmail($in){
	  if ( filter_var($in, FILTER_VALIDATE_EMAIL ) ){
	    return true;
	  }else{
	    return false;
	  }
	}
	
	public function writeData($array = array(), $type = 'json')
	{ 
	  if($type == 'json')
	    print_r(json_encode($array));
	  else if($type == 'html'){
	    $out  = "";
			$out .= "<table>";
			foreach($array as $key => $element){
			  $out .= "<tr>";
			  foreach($element as $subkey => $subelement){
			    $out .= "<td>$subelement</td>";
			  }
			  $out .= "</tr>";
			}
			$out .= "</table>";
			echo $out;
	  } elseif($type == 'xml'){
	    header('Content-type: text/xml');
	    print_r($this->array2xml($array));
	  }
	}
	
	private function array2xml($array, $xml = false)
	{
	  if($xml === false){
	    $xml = new \SimpleXMLElement('<root/>');
	  }
	  foreach($array as $key => $value){ 
	    if(is_array($value)){
	      $this->array2xml($value, $xml->addChild($key));
	    }else{
	      $xml->addChild($key, $value);
	    }
	  }
	  return $xml->asXML();
	}
	
	public function readCsv($filePath)
	{
		ini_set('memory_limit', '500M');
		$data = array();
		if(($handle = fopen($filePath, "r")) !== FALSE){
			while (($row = fgetcsv($handle, 2000, ",")) !== FALSE) { 
				$data[] = $row;
			}
			fclose($handle);
		}
		return $data;
	}
	
	public function readExcel($filePath)
	{
		$arraydata = array();
		
		if(file_exists($filePath)){
			// Initiate cache
			$cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
			$cacheSettings = array( 'memoryCacheSize' => '32MB');
			\PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
			
			$inputFileType = \PHPExcel_IOFactory::identify($filePath);
			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);  
			$objReader->setReadDataOnly(true);

			/**  Load $inputFileName to a PHPExcel Object  **/  
			$objPHPExcel = $objReader->load($filePath);
			
			//Sheet options
			//$total_sheets=$objPHPExcel->getSheetCount(); 
			//$allSheetName=$objPHPExcel->getSheetNames(); 
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();  
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
			
			//Cells which contains data format
			$dateCell = array(6,7,20,21,22,23);
			
			for ($row = 0; $row <= $highestRow;++$row) 
			{  
				for ($col = 0; $col < $highestColumnIndex;++$col)
				{  
					$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
					if(in_array($col,$dateCell)){
						//Very strange, but phpexcel returs the date -1 day!!
						$value = date('n/d/y',\PHPExcel_Shared_Date::ExcelToPHP($value)+86400);
					}
					$arraydata[$row-1][$col]=$value;
				}
			}
		}
		return $arraydata;
	}

	public function uploadFile($path)
	{
		try {
      // Undefined | Multiple Files | $_FILES Corruption Attack
      // If this request falls under any of them, treat it invalid.
      if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error']))
			{
        throw new \RuntimeException('Invalid parameters.');
      }
  
      // Check $_FILES['file']['error'] value.
			$code = $_FILES['file']['error']; 
      switch ($code) { 
				case UPLOAD_ERR_INI_SIZE: 
					throw new \RuntimeException("The uploaded file exceeds the upload_max_filesize directive in php.ini"); 
					break; 
				case UPLOAD_ERR_FORM_SIZE: 
					throw new \RuntimeException("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"); 
					break; 
				case UPLOAD_ERR_PARTIAL: 
					throw new \RuntimeException("The uploaded file was only partially uploaded"); 
					break; 
				case UPLOAD_ERR_NO_FILE: 
					throw new \RuntimeException("No file was uploaded"); 
					break; 
				case UPLOAD_ERR_NO_TMP_DIR: 
					throw new \RuntimeException("Missing a temporary folder"); 
					break; 
				case UPLOAD_ERR_CANT_WRITE: 
					throw new \RuntimeException("Failed to write file to disk"); 
					break; 
				case UPLOAD_ERR_EXTENSION: 
					throw new \RuntimeException("File upload stopped by extension"); 
					break; 	
			}
  
      // You should name it uniquely.
      // On this example, obtain safe unique name from its binary data.
      $path = $path.$_FILES['file']['name'];
      $dirname = dirname($path); 
      if (!is_dir($dirname))
      {
        mkdir($dirname, 0777, true);
      }
      
      if (!move_uploaded_file($_FILES['file']['tmp_name'],$path)) {
        throw new \RuntimeException('Failed to move uploaded file.');
      }
  
      return $path;
  
    } catch (\RuntimeException $e) {
      print_r($e->getMessage());
			exit;
    }
	}
	
	/**
	 *	Write in csv file and store it
	 *
	 *	@param $filePath filename and filepath
	 *	@param $list data in array format
	 */
	public function writeCsvFile($filePath,$list = array())
	{
		// On this example, obtain safe unique name from its binary data.
		$dirname = dirname($filePath); 
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0777, true);
		}
		
		$fp = fopen($filePath, 'w');
	
		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
		
		return $filePath;
	}
	

	/**
	 *	Unzip file
	 *
	 *	@param $zipped string file path and file name of zipped file
	 *	@param $unzipto string where to unzip
	 */
	public function unzipFile($zipped = null, $unzipto = null){
	  
	  $zip = new \ZipArchive;
	  $zip->open($zipped);
	  $fileName =  $zip->getNameIndex(0);
	  $zip->extractTo($unzipto);
	  $zip->close();
	  
	  //Unzip the file
	  //  $zip_cmd = `unzip -o $zipped -d $unzipto`;
	  //  $zip_cmd = str_replace('inflating: ','',$zip_cmd); print_r('--'.trim($zip_cmd).'--');die;
	  return $fileName;
	}
	

	/*
	 *	Global debuging method
	 *
	 *	@param $string string
	 *	@param $error_log bool 
	 */
	public function print_str($string,$error_log = false){
		if($error_log == true){
			error_log(print_r($string,true));
			error_log(printf("\n"));
		} else {
			print_r($string);
			printf("\n");
		}
	}

	/** gzipFile 
	 * 
	 * gzip a file in place
	 *
	 * @param string path to file to gzip
	 * @return new path with .gz 
	 **/
	public function gzipFile($file){
	  if(file_exists($file . '.gz')) unlink($file . '.gz');
	  if(file_exists($file)){
	    `gzip -f9 {$file}`;
	  }
	  if(file_exists( $file.'.gz') ){
	    return $file.'.gz';
	  }
	}

	/** unGzipFile 
	 * 
	 * gzip decompress a file in place
	 *
	 * @param string path to file to gzip decompress
	 * @return new path without .gz 
	 **/
	public function unGzipfile($file){
	  if(file_exists($file)){
	    $out = trim($file,".gz");
	    `gzip -dc {$file} > {$out}`;
	  }
	}


	/** postToS3()
	 * 
	 * push a local file to S3
	 *
	 * @param string path to file locally 
	 * @param string path to target dir on s3
	 * @return string full path of new file on s3
	 **/
	public function postToS3($local_file, $s3_path){

	  if( !file_exists($local_file) ) die ('no such local file'.$local_file."\n");
	  $file = array_pop( @explode('/',$local_file) ) ;

	  $s3client = S3Client::factory( $this->returnAwsConfig() ); 
	  $out = $s3client->putObject( array( 'Bucket' => S3_BUCKET,
					      'Key'    => $s3_path.'/'.$file,
					      'SourceFile'   => $local_file ) 
				       );

	  $md5 =   md5( file_get_contents( $local_file ) ); // CHECKSUM LOCAL FILE
	  $s3Md5 = str_replace('"','', $out['ETag']);  // GET RID OF DOUBLE QUOTES AROUND VALUE

	  if ( $s3Md5 == $md5){
	    return $s3_path.'/'.$file;
	  }else{
	    die( "ERROR TRANSFERING: ".$local_file."\n".print_r($out,true) );
	  }
	}

	/** getFromS3() 
	 * 
	 * pull a file from S3 to local - untested
	 *
	 * @param string path to file on s3
	 * @param string path to local file to write
	 * @return mixed content of file or results of writing to local FS 
	 **/
	public function getFromS3($s3_file, $local_file=null){
	  $s3client = S3Client::factory( $this->returnAwsConfig() ); 
	  $out = $s3client->getObject( array( 'Bucket' => S3_BUCKET,
					      'Key'    => $s3_file )
				       );
	  if(!empty($local_file)){
	    return file_put_contents($local_flie, $out['Body']);
	  }else{
	    return $out['Body'];
	  }
	}

	/** returnAwsConfig()
	 * 
	 * return array of config settings for feeding to client factory
	 *  eg) $s3 = s3Client::factory( $this->returnAwsConfig() );
	 **/
	public function returnAwsConfig(){
	  return array( 'key'    => AWS_ACCESS_KEY, 
			'secret' => AWS_SECRET_KEY, 
			'region' => AWS_REGION_DEFAULT); 
	}

	public function download($file)
	{ 
		//This will force download in Safari
    header('content-type: application/octet-stream');
    header('content-Disposition: attachment; filename='.basename($file));
    header('Pragma: no-cache');
    header('Expires: 0');
    readfile($file); 
    exit;
	}

	/**
	* PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
	* $algorithm - The hash algorithm to use. Recommended: SHA256
	* $password - The password.
	* $salt - A salt that is unique to the password.
	* $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
	* $key_length - The length of the derived key in bytes.
	* $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
	* Returns: A $key_length-byte key derived from the password and salt.
	*/
 public function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
 {
	 $salt = base64_decode($salt);
	 $algorithm = strtolower($algorithm);
	 if(!in_array($algorithm, hash_algos(), true))
		die('PBKDF2 ERROR: Invalid hash algorithm.');
	 if($count <= 0 || $key_length <= 0)
		die('PBKDF2 ERROR: Invalid parameters.');
 
	 $hash_length = strlen(hash($algorithm, "", true));
	 $block_count = ceil($key_length / $hash_length);
 
	 $output = "";
	 for($i = 1; $i <= $block_count; $i++)
	 {
		 // $i encoded as 4 bytes, big endian.
		 $last = $salt . pack("N", $i);
		 // first iteration
		 $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
		 // perform the other $count - 1 iterations
		 for ($j = 1; $j < $count; $j++) {
			 $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
		 }
		 $output .= $xorsum;
	 }
 
	 if($raw_output)
		return substr($output, 0, $key_length);
	 else
		return bin2hex(substr($output, 0, $key_length));
	}
	
	public function mkdir($path)
	{
		$dirname = dirname($path);
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0777, true);
		}
	}
	
	public function writeToFile($path,$url)
	{
		$this->mkdir($path);
		file_put_contents($path, file_get_contents($url));
		return $path;
	}
	
}

