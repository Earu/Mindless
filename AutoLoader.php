<?php

//this permits to load your files automatically

include __DIR__ ."/_conf/Constants.php";

function __autoload($class){
  if(strpos($class, "Controllers") === 0 || strpos($class, "Services") === 0 || strpos($class, "Objects") === 0){
    include __DIR__. '/public/' . $class . '.php';
  }
  else{
    include __DIR__ . '/application/' . $class . '.php';
  }
}

if(apcu_fetch("RouteManager") != null && ($ENV_PRODUCTION || !$REBUILD)){
  $ViewManager = apcu_fetch("ViewManager");
  $RouteManager = apcu_fetch("RouteManager");
  $TemplateManager = apcu_fetch("TemplateManager");
}
else{
  $ViewManager = new View\ViewManager();
  $RouteManager = new Route\RouteManager();
  $TemplateManager = new Template\TemplateManager();

  if($ENV_PRODUCTION){
    apcu_store("ViewManager", $ViewManager);
    apcu_store("RouteManager", $RouteManager);
    apcu_store("TemplateManager", $TemplateManager);
  }
}
 ?>
