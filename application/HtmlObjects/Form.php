<?php namespace HtmlObjects;

use \View\HtmlWriter;

class Form extends ImbricableObject {
  public function __construct($type, $parameters){
    parent::__construct($type, $parameters);
  }
  public function GenerateRequest(){
    return new FormRequest($this->attributes->handle, $this);
  }
  public function ToHtml(){
    $html = "<form action='postForm.php' method='POST' ";
    if(isset($this->attributes->action)){
      unset($this->attributes->action);
    }
    foreach($this->attributes as $attribute => $value){
      $html .= $attribute . "='" . $value . "'";
    }
    $html .= '>';
    foreach($this->content as $htmlObject){
      $html .= $htmlObject->ToHtml();
    }
    $this->GenerateRequest();
    $html .= '<input type="hidden" value="' . $this->name . '" name="requestId"/></form>';

    return $html;
  }
}


 ?>
