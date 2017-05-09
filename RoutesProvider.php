<?php

//this: RoutesManager
//SetDefault(name, fileUrl, ControllerInstance)
//Set the default page (like index.html)
$this->SetDefault("login", "login.html", '\Controllers\LogIn');

// SetRoute(name, fileUrl, controllerInstance)
$this->SetRoute("home", "home.html", "\Controllers\Home");

 ?>
