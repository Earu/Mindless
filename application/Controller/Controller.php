<?php namespace Controller;

use \View\HtmlWriter;
use \Session\Session;

class Controller{
  protected $services = array();
  protected $request;

  public function __construct(){
    $session = Session::GetInstance();
  }
  protected function SetService($name, $value){
    $this->services[$name] = $value;
    apcu_store("Service[". $name . "]", $value);
    return $value;
  }
  protected function GetService($name){
    $name[0] = strtoupper($name[0]);
    if(isset($this->services[$name])){
      return $this->services[$name];
    }
    return null;
  }
  public function Parse($pageContent){
    $importSvcPattern = "/\[import\sService\[\w*\]\]/";
    $varPattern = "/\{\{\s[a-zA-Z0-9$;]+\s\}\}/";
    $ftnPattern = "/\{{2}\s[a-zA-Z0-9\/\$\->:\.\"\',()\s=\]\[]+;\s\}{2}/";

    /* Matching services imports */
    preg_match_all($importSvcPattern, $pageContent, $importDetection);
    $importDetection = $importDetection[0];
    for($i = 0;$i < sizeof($importDetection);$i++){
      if($importDetection[$i] != null){
        $new = str_replace(['[import Service[', ']]'], '', $importDetection[$i]);
        $new[0] = strtoupper($new[0]);
        $className = 'Services\\' . $new;
        $this->SetService($new, new $className());
        $pageContent = str_replace($importDetection[$i], '', $pageContent);
        HtmlWriter::ClientLogStyle("[PHP] Import service " . $className, "color: green;");
      }
    }

    global $SERVICE_AUTO_IMPORT;
    for($i = 0;$i < sizeof($SERVICE_AUTO_IMPORT);$i++){
      $name = $SERVICE_AUTO_IMPORT[$i];
      $name[0] = strtoupper($name[0]);
      $className = 'Services\\' . $name;
      if($this->GetService($name) === null){
        $this->SetService($name, new $className());
        HtmlWriter::ClientLogStyle("[PHP] Auto Import service " . $className, "color: green;");
      }
    }

    /* Matching functions */
    preg_match_all($ftnPattern, $pageContent, $ftnDetection);
    $ftnDetection = $ftnDetection[0];
    foreach($ftnDetection as $value){
      if($value != null){
        $new = str_replace(['{{ ', ' }}'], '', $value);
        if($new[0] == '$'){
          $new = eval("return " .$new);
        }
        else{
          $new = str_replace($new, substr($new, 1, strpos($new, '();') - 1), $new);
          try{
            $new = $new();
          }catch(Exception $e){
            $new = "<br /><p>ERROR :: function " . $new . " not found</p><br />";
          }
        }
        $pageContent = str_replace($value, $new, $pageContent);
      }
    }

    /* Matching vars */
    preg_match_all($varPattern, $pageContent, $varDetection);
    foreach($varDetection as $value){
      if(sizeof($value) > 0){
        $new = str_replace(['{{', '}}'], '', $value[0]);
        $varName = substr($new, 2, strpos($new, ';') - 2);
        if(isset($GLOBALS[$varName])){
          $new = str_replace($new, $GLOBALS[$varName], $new);
        }
        else{
          $new = str_replace($new, "<br /><p>ERROR :: variable " . $varName . " not found</p><br />", $new);
        }
        $pageContent = str_replace($value, $new, $pageContent);
      }
    }

    return $pageContent;
  }
}
 ?>
