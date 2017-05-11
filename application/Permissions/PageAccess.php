<?php namespace Permissions;

use \Route\RouteManager;

class PageAccess
{
	private $Types;
	private $UserType;
	private $URLs;
	
	public function __construct()
	{
		global $ACCOUNT_TYPE;
		$this->Types = $ACCOUNT_TYPE;
		$this->URLs = array();
		
		$this->Init();
		$this->DefaultPerms();

		if(!$this->IsUserAllowed()){
			header("Location: ".RouteManager::GetInstance()->GetDefault()["url"]);
		}
		
	}

	public function SetCurrentUserType($usertype){
		$this->UserType = ((isset($usertype) && $this->IsExistingType($usertype)) ? $usertype : $this->Types[0]);
	}

	private function IsExistingType($type){
		foreach ($this->Types as $key => $value) {
			if($type === $value){
				return true;
			}
		}
		return false;
	}

	public function SetDefaultPerms(){
		$levels = (empty(func_get_args()) ? [ 0 => 0] : func_get_args());
		
		foreach (RouteManager::GetInstance()->GetAll() as $key => $value){
			if(empty($this->URLs[$value["url"]])) {
				foreach ($levels as $index => $level) {
					$this->AddPerm($value["url"],$level);
				}	
			}
		}
	}

	public function AllowAll($url){
		if($this->IsException($url)){
			$this->RemoveException($url);
		}
		
		foreach ($this->Types as $key => $value) {
			$this->AddPerm($url, $key);
		}
	}

	public function AddPerm($url,$level){
		if($this->IsException($url)){
			$this->RemoveException($url);
		}
		
		if(isset($this->Types[$level])){
			array_push($this->URLs[$url],$this->Types[$level]);
		}
	}

	public function RemovePerm($url,$level){
		if(!$this->IsException($url)){
			foreach ($this->URLs[$url] as $key => $value) {
				if($level == $key){
					unset($this->URLs[$url][$key]);
				}
			}
		}
	}

	public function AddException($url){
		$this->URLs[$url][0] = "EXCEPTION";
	}

	public function RemoveException($url){
		if($this->IsException($url)){
			unset($this->URLs[$url][0]);
		}
	}

	private function IsException($url){
		return (isset($this->URLs[$url][0]) && $this->URLs[$url][0] == "EXCEPTION");
	}

	private function IsUserAllowed(){
		$url = substr($_GET["page"],1);
		
		if($this->IsException($url)){
			return true;
		}elseif(!isset($this->URLs[$url])){
			return false;
		}else{
			foreach ($this->URLs[$url] as $key => $value) {
				if($value === $this->UserType){
					return true;
				}
			}
			return false;
		}	
	}

	private function Init(){

		foreach (RouteManager::GetInstance()->GetAll() as $key => $value) {
			$this->URLs[$value["url"]] = array();
		}

		$this->AllowAll("home");
		include __DIR__."/../../Permissions.php";

	}

	private function DefaultPerms(){
		$this->SetDefaultPerms(0);
	}

}
?>