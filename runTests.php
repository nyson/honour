<?
define("TEST_DIRECTORY", "./tests/");
error_reporting(E_ALL);


$tests = opendir(TEST_DIRECTORY);

while($test = readdir($tests)) {
  if(preg_match("/.+\.php/", $test)) {
    echo "Executing test '$test':\n";
    include(TEST_DIRECTORY.$test);
  }
}

