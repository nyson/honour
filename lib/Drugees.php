<?
require_once("./lib/Drugee.php");

class Drugees {
  public $drugees;

  public function __construct(){
    $this->populateWithDefaultValues();
  }

  public function getAllAsHTML() {
    $out = "";
    foreach($this->drugees as $d) {
      $out .= $d->toHTML() . "\n";
    }

    return $out;
    
  }
  
  private function loadFromDatabase(){
    
  }
  
  private function populateWithDefaultValues(){
    $format = "Ymd H:i";
 

    $drugeesRaw = array
      (array("name" => "Jonathan Skårstedt", 
             "email" => "sirnyson@gmail.com", 
             "since" => DateTime::createFromFormat($format, "20130305 00:00")
             ), 
       array("name" => "Anton Ekblad",
             "email" => "anton@ekblad.cc",
             "since" => DateTime::createFromFormat($format, "20130305 00:00")
             ), 
       array("name" => "Gustav Johansson",
             "email" => "",
             "since" => DateTime::createFromFormat($format, "20130305 12:00")
             ), 
       array("name" => "Behrouz Talebi",
             "email" => "berrat2@gmail.com",
             "since" => DateTime::createFromFormat($format, "20130305 14:00")
             ),  
       array("name" => "Tove Ekblad",
             "email" => "tove@ekblad.cc",
             "since" => DateTime::createFromFormat($format, "20130305 17:00")
             )
       );    

    foreach($drugeesRaw as $d) {
      $this->drugees[] = new Drugee($d['name'], $d['email'], $d['since']);
    }
  }
}