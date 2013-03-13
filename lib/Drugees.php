<?
require_once("./lib/Drugee.php");
require_once("./lib/SQL.php");
class Drugees {
  protected $drugees;
  protected $db;

  public function __construct(){
    $this->db = SQL::getInstance();
    $this->loadFromDatabase();
  }

  public function getAllAsHTML() {
    $out = "";
    foreach($this->drugees as $d) {
      $out .= $d->toHTML() . "\n";
    }
    return $out;
  }
  
  protected function loadFromDatabase(){
    $drugees = array(); $this->drugees = array();
    $users = $this->db->query("SELECT id, name, email FROM users");
    $userids = array();
    
    while($r = $users->fetch_assoc()){
      $userids[] = $r['id'];
      $drugees[$r['id']] = array
	("name" => $r['name'],
	 "email" => $r['email'],
	 "since" => array());
    }

    $resets = $this->db->query
      ("SELECT user, timestamp FROM resets WHERE "
       . "user IN (".implode($userids, ', ').") "
       . "ORDER BY timestamp ASC");
    
    if($this->db->error) {
      throw new BadQueryException("Could not execute last query!");
    }
    while($r = $resets->fetch_assoc()) {
      $drugees[$r['user']]['since'][] 
	= DateTime::createFromFormat('U', $r['timestamp']);
    }

    foreach($drugees as $d) {
      $this->drugees[] = new Drugee($d['name'], $d['email'], $d['since']);
    }
  }
}