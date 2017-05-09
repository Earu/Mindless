<?php namespace Session;

use \View\HtmlWriter;

class Session{
  private static $instance;
  public $ID;

  public function __construct(){
    session_start();
    $this->ID = session_id();
  }
  public function GetData($name){
    if(isset($_SESSION[$name])){
      return $_SESSION[$name];
    }
    else{
      return null;
    }
  }
  public function SetData($name, $value){
    if(gettype($value) == 'string'){
      $value = htmlentities($value);
    }
    $_SESSION[$name] = $value;
  }
  public function DestroyAll(){
    foreach($_SESSION as $key => $value){
      unset($_SESSION[$key]);
    }
  }
  public function DestroyData($key){
    if(isset($_SESSION[$key])){
      unset($_SESSION[$key]);
    }
  }
  public static function GetInstance(){
    return (self::$instance === null ? (self::$instance = new Session()) : self::$instance);
  }
}

 ?>
