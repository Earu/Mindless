<?php namespace HtmlObjects;

use \View\HtmlWriter;

class ImbricableObject extends HtmlObject{
  protected $content = array();

  public function __construct($type, $parameters){
    parent::__construct($type, $parameters);
  }
  public function GetRequiredData(){
    $final = array();
    for($i = 0;$i < sizeof($this->content);$i++){
      if(HtmlObject::IsImbricable($this->content[$i]->type)){
        $requiredData = $this->content[$i]->GetRequiredData();
        for($j = 0;$j < sizeof($requiredData);$j++){
          array_push($final, $requiredData[$j]);
        }
      }
      else if(isset($this->content[$i]->attributes->required)){
        array_push($final, $this->content[$i]);
      }
    }
    return $final;
  }
  public function Add($content){
    array_push($this->content, $content);
  }
  public function Remove($content){
    for($i = 0;$i < sizeof($this->formContent);$i++){
      if($this->content === $content){
        $this->content.splice($i, 1);
        return;
      }
    }
    if(isset($content->name)){
      HtmlWriter::ClientLog("Cannot remove element [Object name: '" . $content->name . "']");
    }
    else{
      HtmLWriter::ClientLogStyle("Object doesn't have name value", "color: white; background-color: white;");
    }
  }
  public function AddToChild($object){
    for($i = 0;$i < sizeof($this->content);$i++){
      if($this->content[$i]->name == $object->parent){
        $this->content[$i]->Add($object);
      }
      else if(HtmlObject::IsImbricable($this->content[$i]->type)){
        $this->content[$i]->AddToChild($object);
      }
    }
  }
  public function Contains($object){
    if(isset($object->parent)){
      for($i = 0;$i < sizeof($this->content);$i++){
        if($this->content[$i]->name == $object->parent){
          return true;
        }
        else if(HtmlObject::IsImbricable($this->content[$i]->type)){
          if($this->content[$i]->Contains($object)){
            return true;
          }
        }
      }
    }
    return false;
  }
  public function ToHtml(){
    $html = "<" . $this->type . " ";
    foreach($this->attributes as $attribute => $value){
      $html .= $attribute . "='" . $value . "' ";
    }
    $html .= '>' . (isset($this->htmlContent) ? $this->htmlContent : "");

    foreach($this->content as $htmlObject){
      $html .= $htmlObject->ToHtml();
    }
    $html .= '</' . $this->type . '>';
    return $html;
  }
}


 ?>
