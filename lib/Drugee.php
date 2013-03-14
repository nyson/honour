<? 
class NotATimestampException extends Exception {}
/**
 * Class for creating and formatting drugees
 */

class Drugee {
  public $name, $lastInterval, $email, $id;
  public $resets = array();
  public $lastBreach;
  
  public function __construct($id, $name, $email, $resets){
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->addResets($resets);
  }

  private function addResets($resets) {
    foreach($resets as $reset) {
      if(get_class($reset) === "DateTime") {
	$this->lastBreach = $resets[$reset->getTimestamp()] = $reset;
	$this->resets[] = $reset;

      } else if(is_numeric($reset) && !ctype_digit($reset)) {
	$this->lastBreach = $resets[$reset] = D0ateTime::createFromFormat('U', $reset);
	$this->resets[] = $reset;
      } else 
	throw new Exception("Not a timestamp or date");
    }

  }

  private function gravHashGen($email) {
    return md5(strtolower(trim($email)));
  }

  public function toHTML(){
    return "<div class='drugee'>"
      . "<h2>$this->name</h2>"
      . "<img src='http://www.gravatar.com/avatar/"
      . $this->gravHashGen($this->email)
      . " alt='Image for $this->name' />"
      . "<p class='count'>"
      . "<span class='motivator'>Currently not giving in to temptation for</span><br />"
      . $this->timeDiffHTML($this->lastBreach)
      . "</div>"

      ;
  }

  public function toDataArray(){
    $resout = array();

    foreach($this->resets as $r) {
      $resout[] = $r->getTimestamp();
    }
    return array("name" => $this->name,
		 "gravatarHash" => $this->gravHashGen($this->email),
		 "email" => $this->email,
		 "resets" => $resout,
		 "lastBreach" => $this->lastBreach->getTimestamp());
  }

  private function intervalHTML(DateInterval $diff) {
    $days = $diff->format("%a");
    if($days >= 7) {
      $weeks = floor($days / 7);
      $weekDays = $days % 7;

      $timestring = "<span class='days'>$weeks</span>"
	. '<span class="quantifier"> weeks'
	. ($weekDays !== 0 ? ' and </span>' : "</span>");
	
      if($weekDays !== 0) {
	$timestring .= "<span class='hours'>$weekDays</span>"
	. '<span class="quantifier"> days</span>';
      }
      return $diff->format($timestring);

    } else if ($days >= 1) {
      return $diff->format('<span class="days">%a</span>'
			   . '<span class="quantifier"> days and </span>'
			   . '<span class="hours">%h</span>'
			   . '<span class="quantifier"> hours</span>');
      
    } else {
      return $diff->format('<span class="days">%h</span>'
			   . '<span class="quantifier"> hours and </span>'
			   . '<span class="hours">%I</span>'
			   . '<span class="quantifier"> minutes</span>');
      
    }

  }

  private function timeDiffHTML(DateTime $time,
				DateTime $diff = null) {
    if($diff === null)
      $diff = new DateTime("now");
    return $this->intervalHTML($diff->diff($time));
  }
}