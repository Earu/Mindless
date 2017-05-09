<?php
include __DIR__ . "/../_conf/Constants.php";
include __DIR__ . "/../application/HtmlObjects/FormRequest.php";
include __DIR__. "/../application/View/HtmlWriter.php";
include __DIR__. "/../application/Session/Session.php";

if(isset($_POST)){
  if(strpos($_SERVER['HTTP_REFERER'], '?') !== FALSE){
    $_SERVER['HTTP_REFERER'] = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '?'));
  }
  if(isset($_POST['requestId'])){
    $session = Session\Session::GetInstance();
    $request = $session->GetData('Request[' . $_POST['requestId'] . ']');
    echo $request !== null ? "true" : "false";

    if($request !== null){
      foreach($_POST as $key => $value){
        $request->AddParams($key, htmlentities($value));
      }
      $request->Executing = TRUE;
      $request->Save();
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    else{
      echo "Security error";
    }
  }
}


 ?>
