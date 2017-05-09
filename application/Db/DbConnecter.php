<?php namespace Db;

use \Db\DataReader;
use \Db\Statement;
use \View\HtmlWriter;
use \Session\Session;
require __DIR__ . "/../../_conf/DbConfig.php";
use \conf\DbConfig;

class DbConnecter{
  private $connected = true;
  private $pdo;
  private $bdd;

  public function __construct(){
    try{
      $this->pdo = new \PDO(
        DbConfig::$dbType .
        ":host=" . DbConfig::$host .
        ';dbname=' . DbConfig::$dbName .
        ';default_charset=utf8',
        DbConfig::$username,
        DbConfig::$password,
        array(
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_TIMEOUT => 3
        )
      );
      $this->connected = true;
    }
    catch(\PDOException $err){
      $this->connected = false;
    }
  }
  public function IsConnected(){
    return $this->connected;
  }
  public function Select($statement){
    if($this->connected){
      if(get_class($statement) == 'Db\\Statement'){
        $query = $this->pdo->prepare($statement->build());
        $query->execute();

        return new DataReader($query->fetchAll());
      }
      else{
        throw new \InvalidArgumentException();
      }
    }
    return $GLOBALS["FORM_ERRORS"][5];
  }
  public function Insert($statement){
    if(get_class($statement) == 'Db\\Statement'){
      $query = $this->pdo->prepare($statement->build());
      $query->execute();
      return $query;
    }
  }
  public function Delete($statement){
    if(get_class($statement) == 'Db\\Statement'){
      $query = $this->pdo->prepare($statement->build());
      $query->execute();

      return $query;
    }
  }
}

 ?>
