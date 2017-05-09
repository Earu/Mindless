<?php

include __DIR__.'/../AutoLoader.php';
//if 404 error or no page specified, redirected to default page
//else show page
if(isset($_GET['page']) && $_GET['page'] != '/mainController.php' && $_GET['page'] != '/'){

  if(($index = strpos($_GET['page'], '/')) == 0 && $index !== FALSE){
    if(!$RouteManager->Redirect(substr($_GET['page'], $index + 1))){
      header('Location: /' . $RouteManager->GetDefault()['url']);
    }
  }
}
else{
  header('Location: /' . $RouteManager->GetDefault()['url']);
}
?>
