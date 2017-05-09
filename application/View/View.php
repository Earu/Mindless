<?php namespace View;

use \HtmlObjects\HtmlObject;

class View{
  public $htmlObjects = array();
  private $content;
  private $controller;
  public $name;
  public $url;

  public function __construct($url, $name, $controller){
    $this->controller = $controller;
    $this->name = $name;
    $this->url = $url;
  }
  public function SetObjects($collection){
    $this->htmlObjects = $collection;

    for($i = 0;$i < sizeof($collection);$i++){
      if(HtmlObject::IsImbricable($collection[$i]->type)){
        $this->content = str_replace('{{[' . $i . ']}}', $collection[$i]->ToHtml(), $this->content);
      }
    }
  }
  public function SetContent($content){
    $this->content = $content;
  }
  public function GetContent(){
    return $this->content;
  }
  public function GetController(){
    return $this->controller;
  }
}

 ?>
