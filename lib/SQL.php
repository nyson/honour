<?

require_once("./config.php");

/**
 * Uses mysqli through a singleton with config data
 */
class SQL extends Mysqli{
  private static $instance;
  private function __construct(){
    parent::__construct(DB_HOST, DB_USER, DB_PASS);
    $this->select_db(DB_DB);
  }
  
  
  public static function getInstance(){
    return isset($instance)
      ? $instance
      : new SQL();
  }
}