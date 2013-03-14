<? 

require_once("./lib/Drugees.php");

class DrugeesTest extends Drugees{
  public function __construct(){
    $this->db = SQL::getInstance();
    /*  
	echo "* Populating with default values...\n";
	$this->populateWithDefaultValues();
	$this->insertCurrentValuesIntoDB();
    */
    echo "* Fetching results...\n";
    $this->loadFromDatabase();
    echo "* Fetched values: \n";
    $this->printValues();
  }

  protected function printValues() {
    foreach($this->drugees as $d) {
      echo "\t$d->name, $d->email\n";
      foreach($d->resets as $r) {
	echo "\t\tReset: " . $r->getTimestamp() . "\n";
      }
    }
  }
  
  protected function populateWithDefaultValues(){
    $format = "Ymd H:i";
    $drugeesRaw = array
      (array("name" => "Jonathan Skårstedt", 
             "email" => "sirnyson@gmail.com", 
             "since" => array(DateTime::createFromFormat($format, "20130305 00:00"))
             ), 
       array("name" => "Anton Ekblad",
             "email" => "anton@ekblad.cc",
             "since" => array(DateTime::createFromFormat($format, "20130305 00:00"),
			      DateTime::createFromFormat($format, "20130311 01:02")
			      )
             ), 
       array("name" => "Gustav Johansson",
             "email" => "",
             "since" => array(DateTime::createFromFormat($format, "20130305 12:00"))
             ), 
       array("name" => "Behrouz Talebi",
             "email" => "berrat2@gmail.com",
             "since" => array(DateTime::createFromFormat($format, "20130305 14:00"))
             ),  
       array("name" => "Tove Ekblad",
             "email" => "tove@ekblad.cc",
             "since" => array(DateTime::createFromFormat($format, "20130305 19:08")
			      )
             )
       );    

    foreach($drugeesRaw as $d) {
      $this->drugees[] = new Drugee
	($d['name'], 
	 $d['email'], 
	 $d['since']);
    }
  }


  protected function insertCurrentValuesIntoDB(){
    $resets = array();
    $this->db->query("TRUNCATE TABLE users");
    $this->db->query("TRUNCATE TABLE resets");
    
    foreach($this->drugees as $drugee) {
      $hash = md5("agjköldakglöadkgpegage" . md5($drugee->name . $drugee->email));
      $this->db->query("INSERT INTO users (name, email, hash) "
		       . "VALUES ('{$drugee->name}', '{$drugee->email}', '$hash')");

      
      $user = $this->db->insert_id;
      $tslast; $largestInterval = 0;

      foreach($drugee->resets as $reset) {
	$ts = $reset->getTimestamp();
	if(isset($tslast) && $ts - $tslast > $largestInterval)
	  $largestInterval = $ts - $tslast;

	$this->db->query("INSERT INTO resets (user, timestamp) "
			 . "VALUES ('$user', '$ts')");

	$tslast = $ts;
      }
      
      if(isset($tslast) && (new DateTime('now'))->getTimestamp() - $tslast > $largestInterval)
	$largestInterval = (new DateTime('now'))->getTimestamp() - $tslast;
      
      $this->db->query("UPDATE users SET largestInterval = $largestInterval WHERE id = $user");      
    }
  }

}

echo "* Trying to create object...\n";
new DrugeesTest();
echo "* Finished creating object. \n";
