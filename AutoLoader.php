<?php

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
  $PageAccess = apcu_fetch("PageAccess");
}
else{
  $ViewManager = new View\ViewManager();
  $RouteManager = new Route\RouteManager();
  $TemplateManager = new Template\TemplateManager();
  $PageAccess = new Permissions\PageAccess();

  if($ENV_PRODUCTION){
    apcu_store("ViewManager", $ViewManager);
    apcu_store("RouteManager", $RouteManager);
    apcu_store("TemplateManager", $TemplateManager);
    apcu_store("PageAccess", $PageAccess);
  }
}
 ?>