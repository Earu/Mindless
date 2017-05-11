<?php namespace HtmlObjects;

use \View\HtmlWriter;

class HtmlObject{
  private static $imbricableObjects = ["DIV", "HEADER", "FOOTER", "SPAN", "P", "FORM", "SELECT", "OPTION", "TEXTAREA"];
  public $type;
  public $name;
  public $attributes;
  public $htmlContent;
  public $parent;

  public function __construct($type, $parameters){
    $this->type = $type;
    $parameters = json_decode(substr($parameters, strpos($parameters, '{')));

    if(!isset($parameters->name)){
      HtmlWriter::ClientLogStyle("HtmlObject [".$type."] doesn\'t have name value", "background-color: red; color: white;");
    }
    else{
      $this->name = $parameters->name;
    }

    if(isset($parameters->parent)){
      $this->parent = $parameters->parent;
      unset($parameters->parent);
    }
    if(isset($parameters->HTML)){
      $this->htmlContent = $parameters->HTML;
      unset($parameters->HTML);
    }
    $this->attributes = $parameters;
  }
  public function ToHtml(){
    $result = '<' . $this->type;
    foreach($this->attributes as $key => $value){
      $result .= ' ' . $key .'="' . $value . '"';
    }
    if(isset($this->attributes->required)){
      $result .= " required";
    }

    $result .= '>' . (isset($this->htmlContent) ? $this->htmlContent : '') . '</' . $this->type . '>';
    return $result;
  }
  public static function IsImbricable($name){
    for($i = 0;$i < sizeof(self::$imbricableObjects);$i++){
      if(self::$imbricableObjects[$i] == $name){
        return true;
      }
    }
    return false;
  }
}

 ?>
