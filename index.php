<?
require_once("./lib/Drugees.php");
$drugees = new Drugees();
  function gravHashGen($in){
    return md5(strtolower(trim($in)));
  }


  function drugee($name, $email, DateTime $since){
    $format = '<span class="days">%a</span><span class="quantifier"> days and </span>'
    . '<span class="hours">%h:%I</span><span class="quantifier"> hours</span>';
  $time = (new DateTime("now"))->diff($since)
                               ->format($format);
?>
<div class="drugee">
  <h2><?= $name ?></h2>
  <img src="http://www.gravatar.com/avatar/<?= gravHashGen($email)?>?s=200" 
       alt="Image for <?= $name?>"/>

  <p class="count">
    <span class="motivator">Currently not giving in to temptation for</span><br />
    <?= $time ?>
  </p>
  <hr />
  <p class="count">
    <span class="motivator">Has at most resisted temptation for</span><br />
    <?= $time ?>
  </p>

</div>
<?
}


?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Honourbound Duel Counter</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div id="leftbar">
    </div>
    <div id="drugees">
    <?= $drugees->getAllAsHTML(); ?>
    </div>
  </body>
</html>