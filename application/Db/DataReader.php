<?php namespace Db;

class DataReader{
  private $currentIndex = 0;
  private $rowCount;
  private $data;

  public function __construct($data){
    $this->data = $data;
    $this->rowCount = sizeof($data);
  }
  public function GetValue($name){
    return $this->data[$this->currentIndex][$name];
  }
  public function Read(){
    $this->currentIndex++;
    return $this->currentIndex < sizeof($this->data);
  }
  public function rowCount(){
    return $this->rowCount;
  }
}


 ?>
