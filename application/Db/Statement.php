<?php namespace Db;

use \View\HtmlWriter;
class Statement{
  private $command;
  private $parameterCollection;

  public function __construct($command){
    $this->command = $command;
    $this->parameterCollection = array();
  }
  public function build(){
    foreach($this->parameterCollection as $key => $value){
      if(strpos($this->command, $key) !== FALSE){
        $this->command = str_replace($key, "\"" . $value . "\"", $this->command);
      }
    }
    return $this->command;
  }
  public function SetParameter($identifier, $value){
    switch(gettype($value)){
      case 'string':
        $this->parameterCollection[$identifier] = str_replace(['"', "'", "\\"], ['\"', "\'", "\\\\"], htmlentities($value));
        break;
      case 'double':
      case 'integer':
      case 'boolean':
        $this->parameterCollection[$identifier] = $value;
        break;
      default:
        throw new \InvalidArgumentException();
    }
  }
}

 ?>
