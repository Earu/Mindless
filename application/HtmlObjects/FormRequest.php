<?php namespace HtmlObjects;

use \Session\Session;
use \View\HtmlWriter;

class FormRequest{
  private $session;
  private $form;
  public $Executing = FALSE;
  public $params = array();
  public $errors = array();
  public $handleName;

  public function __construct($handleName, $form){
    HtmlWriter::ClientLogStyle("[PHP] Generate FormRequest for " . $handleName, "color: orange;");
    $this->session = Session::GetInstance();
    $this->handleName = $handleName;

    $this->form = $form;
    if(Session::GetInstance()->GetData("Request[" . $form->name . "]") === null){
      $this->Save();
    }
  }
  public function VerifyParams(){
    $requiredData = $this->form->GetRequiredData();
    $found = FALSE;

    for($i = 0;$i < sizeof($requiredData);$i++){
      $found = FALSE;
      foreach($this->params as $key => $value){
        if($key == $requiredData[$i]->name){
          if(isset($requiredData[$i]->attributes->minChar) && strlen($value) < intval($requiredData[$i]->attributes->minChar)){
            array_push($this->errors, [ "type" => $GLOBALS['FORM_ERRORS']["0"], "target" => $requiredData[$i] ]);
          }
          if(isset($requiredData[$i]->attributes->maxChar) && strlen($value) > intval($requiredData[$i]->attributes->maxChar)){
            array_push($this->errors, [ "type" => $GLOBALS['FORM_ERRORS']["1"], "target" => $requiredData[$i] ]);
          }
          if(isset($requiredData[$i]->attributes->pattern) && !preg_match($requiredData[$i]->attributes->pattern, $value)){
            array_push($this->errors, [ "type" => $GLOBALS['FORM_ERRORS']["2"], "target" => $requiredData[$i] ]);
          }
          $found = TRUE;
          break;
        }
      }

      if(!$found){
        array_push($this->errors, [ "type" => $GLOBALS['FORM_ERRORS']["3"], "target" => $requiredData[$i] ]);
      }
    }

    return sizeof($this->errors) == 0;
  }
  public function Save(){
    $this->session->SetData('Request[' . $this->form->name . ']', $this);
  }
  public function GetParams($key){
    return isset($this->params[$key]) ? $this->params[$key] : null;
  }
  public function AddParams($key, $value){
    $this->params[$key] = $value;
  }
}

 ?>
