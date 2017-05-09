<?php namespace controllers;


//a controller is always associated with a page see "routeprovider"
//your controllers must always extend the basic controller class of mindless
class Page extends \Controller\Controller
{
	public function HelloWorld(){
		return "Hello world!";
	}

	public function __construct(){
		$this->HelloWorld(); //the constructor of the controller associated with the page will always be ran on load
		//so this will show hello world on the page associated
	}
}

?>