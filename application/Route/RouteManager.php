<?php namespace Route;

use \Forms\FormRequest;
use \View\HtmlWriter;
use \Session\Session;

class RouteManager{
  private static $self;
  private $default;
  private $routes = array();

  //Initialisation du RouteManager
  public function __construct(){
    $self = $this;
    //Chargement des routes par inclusion du Provider
    include __DIR__ . '/../../RoutesProvider.php';
  }
  public function SetDefault($url, $view, $controllerClassName){
    $this->default = ["view" => $view, "url" => $url, "controller" => $controllerClassName];
  }
  public function SetRoute($url, $view, $controllerClassName){
    if(file_exists(__DIR__ . "/../../public/" .$controllerClassName . ".php")){
      array_push($this->routes, ["view" => $view, "url" => $url, "controller" => $controllerClassName]);
    }
    else{
      HtmlWriter::ClientLogStyle("Error on initialize Controller of route " . $url, "background-color: red; color: white;");
    }
  }
  public function GetDefault(){
    return $this->default;
  }
  public function GetAll(){
    $routes = $this->routes;
    array_push($routes, $this->default);
    return $routes;
  }
  //Affiche la page selon l'url grâce à la liste des routes
  public function Redirect($url){
    global $ViewManager;
    if($this->default['url'] == $url){
      $controller = $this->default['controller'];

      $ViewManager->LoadPage($this->default);
      $view = $ViewManager->GetView($this->default['url']);
      $controller = new $controller();
      echo $controller->Parse($ViewManager->GetView($this->default['url'])->GetContent());

      for($i = 0;$i < sizeof($view->htmlObjects);$i++){
        if($view->htmlObjects[$i]->type === "FORM"){
          HtmlWriter::ClientLog("HtmlObject[FORM] -> " . $view->htmlObjects[$i]->name);
          if(Session::GetInstance()->GetData('Request[' . $view->htmlObjects[$i]->name . ']') === null){
            $view->htmlObjects[$i]->GenerateRequest();
          }
          else{
            $request = Session::GetInstance()->GetData('Request[' . $view->htmlObjects[$i]->name .']');
            if($request->Executing){
              $method = $request->handleName;
              $controller->$method($request);
            }
            Session::GetInstance()->DestroyData('Request[' . $view->htmlObjects[$i]->name .']');
            $view->htmlObjects[$i]->GenerateRequest();
          }
        }
      }
      return true;
    }
    $found = FALSE;
    for($i = 0;$i < sizeof($this->routes) && !$found;$i++){
      if($this->routes[$i]['url'] == $url){
        $found = TRUE;
        $data = $this->routes[$i];

        $controller = $this->routes[$i]['controller'];

        $ViewManager->LoadPage($data);
        $view = $ViewManager->GetView($data['url']);
        $controller = new $controller();
        echo $controller->Parse($ViewManager->GetView($data['url'])->GetContent());

        for($i = 0;$i < sizeof($view->htmlObjects);$i++){
          if($view->htmlObjects[$i]->type == "FORM"){
            if(Session::GetInstance()->GetData('Request[' . $view->htmlObjects[$i]->name . ']') === null){
             $view->htmlObjects[$i]->GenerateRequest();
            }
            else{
              $request = Session::GetInstance()->GetData('Request[' . $view->htmlObjects[$i]->name .']');
              if($request->Executing){
                $method = $request->handleName;
                $controller->$method($request);
              }
              Session::GetInstance()->DestroyData('Request[' . $view->htmlObjects[$i]->name .']');
              $view->htmlObjects[$i]->GenerateRequest();
            }
          }
        }
      }
    }
    return $found;
  }

  public static function GetInstance(){
    return (self::$self === null ? new RouteManager() : self::$self);
  }
}

 ?>
