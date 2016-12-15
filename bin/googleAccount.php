<?php

require_once(dirname(__DIR__).'/config/config.php');
require_once(dirname(__DIR__).'/lib/bootstrap.php');

$gamm = new GoogleAuthManagerModel();

print "Active auth file\n";
print "------------------------------------------------\n";
print_r( $gamm->getActiveAuthFile() );

if(@$argv[1]){
  $gamm->setAuthfile($argv[1]);
  print "New auth file\n";
  print "------------------------------------------------\n";
  print_r( $gamm->getActiveAuthFile() );
}

print "\n\n";
print "Existing auth files\n";
print "------------------------------------------------\n";
print_r ( $gamm->listAuthFiles() );





