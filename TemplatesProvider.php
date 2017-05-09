<?php

//SetDefaultCss($url) : defines an imported css in the folder Views/ on every page
//SetTemplate($name, $fileUrl) : defines a template of the folder Views/Template/ that can be imported on any .html page via [import Template[$name]]

$this->SetDefaultCss("css/default.css");

$this->SetTemplate("navbar","navbar.html");

//Connection form
$this->SetTemplate("ConnectForm", "Forms/ConnectForm.html");


 ?>
