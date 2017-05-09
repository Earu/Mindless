<?php namespace Template;

use \View\HtmlWriter;

class TemplateManager{
  private static $instance;
  private $defaultCss = array();
  private $templates = array();

  public function __construct(){
    self::$instance = $this;
    include __DIR__ ."/../../TemplatesProvider.php";

    $this->FlushTemplates();
  }
  private function FlushTemplates(){
    $importTemplatePattern = "/\[import\sTemplate\[\w*\]\]/";

    foreach($this->templates as $key => $value){
      preg_match_all($importTemplatePattern, $this->templates[$key], $importDetection);
      $importDetection = $importDetection[0];
      foreach($importDetection as $import){
        if($import != null){
          $new = str_replace(['[import Template[', ']]'], '', $import);
          $template = $this->GetTemplate($new);
          $this->templates[$key] = str_replace($import, $template, $this->templates[$key]);
          HtmlWriter::ClientLogStyle("[PHP] Import template " . $new, "color: green;");
        }
      }
    }
  }
  public function GetDefaultCss(){
    $css = "";
    for($i = 0;$i < sizeof($this->defaultCss);$i++){
      $css .= '<link rel="stylesheet" href="/Views/' . $this->defaultCss[$i] .'">';
    }
    return $css;
  }
  public function SetDefaultCss($url){
    array_push($this->defaultCss, $url);
  }
  public function SetTemplate($name, $url){
    $this->templates[$name] = file_get_contents(__DIR__ . "/../../public/Views/Template/" . $url);
  }
  public function GetTemplate($name){
    return (isset($this->templates[$name]) ? $this->templates[$name] : null);
  }
  public static function GetInstance(){
    return (self::$instance === null ? (self::$instance = new TemplateManager()) : self::$instance);
  }
}
 ?>
