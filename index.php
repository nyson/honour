<?
require_once("./lib/Drugees.php");
$drugees = new Drugees();




?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Honourbound Duel Counter</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="./js/main.js"></script>
  </head>
  <body>
    <div id="leftbar">
    </div>
    <div id="drugees">
    <?= $drugees->getAllAsHTML(); ?>
    </div>
  </body>
</html>