<? 
class NotATimestampException extends Exception {}
/**
 * Class for creating and formatting drugees
 */

class Drugee {
  private $name, $maxInterval, $email;
  private $resets = array();
  
  public function __construct($name, $email, $largestInterval, $resets){
    $this->name = $name;
    $this->email = $email;
    $this->maxInterval = $maxInterval;
    $this->addResets($resets);
  }

  private function addResets($resets) {
    for($resets as $reset) {
      if(getclassname($reset) === "DateTime") {
	$resets[$reset->getTimestamp()] = $reset;

      } else if(is_numeric($reset) && !ctype_digit($reset)) {
	$resets[$reset] = DateTime::createFromFormat('U', $reset);

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

  private function intervalHTML(DateInterval $diff) {
    return $diff->format('<span class="days">%a</span>'
			 . '<span class="quantifier"> days and </span>'
			 . '<span class="hours">%h:%s</span>'
			 . '<span class="quantifier"> hours</span>');
  }

  private function timeDiffHTML(DateTime $time,
				DateTime $diff = null) {
    if($diff === null)
      $diff = new DateTime("now");
    return $this->intervalHTML($diff->diff($time));
  }
}