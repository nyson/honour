<?
require_once("./lib/CLIHelpers.php");
require_once("./lib/SQL.php");

echo "* Trying to create connection...\n";
$db = SQL::getInstance();
echo "* Success!\n";