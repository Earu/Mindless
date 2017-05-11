<?php namespace View;

use \HtmlObjects\ImbricableObject;
use \HtmlObjects\HtmlObject;
use \HtmlObjects\Form;

class ViewManager{
  private $pages = array();

  public function LoadPage($route){
    if(apcu_fetch('View[' . $route['url'] .']') && ($GLOBALS["ENV_PRODUCTION"] || !$GLOBALS['REBUILD'])){
      if(!isset($this->pages[$route['url']])){
        $this->pages[$route['url']] = apcu_fetch('View[' . $route['url'] . ']');
      }
    }
    else if(file_exists(__DIR__ . "/../../public/Views/" . $route['view'])){
      HtmlWriter::ClientLogStyle("Loading View :: " . $route['url'], "color: blue");
      $view = $this->PreparePage(new View($route['view'], $route['url'], $route['controller']));

      apcu_store('View[' . $route['url'] . ']', $view);
      $this->pages[$route['url']] = $view;
    }
    else{
      HtmlWriter::ClientLogStyle("Cannot load view file " . $route["view"], "background-color: red; color: white;");
    }
  }
  private function PreparePage($view){
    global $TemplateManager;
    $importTemplatePattern = "/\[import\sTemplate\[\w*\]\]/";
    $objectsPattern = "/(\[\w*\]\{){1}[(a-zA-Z0-9éèàâ\s\t\n\r\-,:\"\'\(\)\@\/\\\{\}\.\+]*\}{1}/";

    //For all pages
    $pageContent = str_replace(["\t", "\n", "\r"], '', file_get_contents(__DIR__ . "/../../public/Views/" . $view->url));
    if(($index = strpos($pageContent, "</head>")) !== FALSE){
      $pageContent = substr($pageContent, 0, $index) . $TemplateManager->GetDefaultCss() . substr($pageContent, $index);
    }
    $objects = array();
    $objectCounter = 0;

    /* Match templates imports */
    preg_match_all($importTemplatePattern, $pageContent, $importDetection);
    $importDetection = $importDetection[0];
    foreach($importDetection as $import){
      if($import != null){
        $new = str_replace(['[import Template[', ']]'], '', $import);
        $template = $TemplateManager->GetTemplate($new);
        $pageContent = str_replace($import, $template, $pageContent);
        HtmlWriter::ClientLogStyle("[PHP] Import template " . $new, "color: green;");
      }
    }

    /* Match all objects */
    $value = preg_match_all($objectsPattern, $pageContent, $objDetection);
    $value = $objDetection[0];
    for($j = 0;$j < sizeof($value);$j++){

      $type = substr($value[$j], 1, strpos($value[$j], ']') - 1);

      if(HtmlObject::IsImbricable($type)){
        if($type == "FORM"){
          $object = new Form("FORM", $value[$j]);
        }
        else{
          $object = new ImbricableObject($type, $value[$j]);
        }

        if(isset($object->parent)){
          $parent = null;
          for($k = 0;$k < sizeof($objects) && $parent === null;$k++){
            if($objects[$k]->name == $object->parent){
              $parent = $objects[$k];
            }
          }

          if($parent != null){
            $parent->Add($object);
          }
          $pageContent = str_replace($value[$j], '', $pageContent);
        }
        else{
          array_push($objects, $object);
          $pageContent = str_replace($value[$j], '{{['.$objectCounter++.']}}', $pageContent);
        }

        HtmlWriter::ClientLogStyle("ImbricableObject[" . $object->type . "] " . $object->name . " created", "color: orange;");
      }
      else{
        $object = new HtmlObject($type, $value[$j]);
        if(isset($object->parent)){
          $found = false;
          for($k = 0;$k < sizeof($objects) && !$found;$k++){
            if($objects[$k]->name === $object->parent){
              $objects[$k]->Add($object);
              $found = true;
            }
            else if(HtmlObject::IsImbricable($objects[$k]->type) && $objects[$k]->Contains($object)){
              $objects[$k]->AddToChild($object);
              $found = true;
            }
          }

          if(!$found){
            HtmlWriter::ClientLogStyle("Cannot found parent ". $object->parent, "background-color: red; color: white;");
          }
          else{
            $pageContent = str_replace($value[$j], '', $pageContent);
          }
        }
        else{
          array_push($objects, $object);
          $pageContent = str_replace($value[$j], '{{['.$objectCounter++.']}}', $pageContent);
        }
      }
    }
    $view->SetContent($pageContent);
    $view->SetObjects($objects);

    return $view;
  }
  public function GetView($name){
    return $this->pages[$name];
  }
}

 ?>
