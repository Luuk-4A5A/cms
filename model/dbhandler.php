<?php

class DbHandler {
  private static $instance = null;
  private $conn = null;
  private $servername = SERVERNAME;
  private $dbname = DBNAME;
  private $username = USERNAME;
  private $password = PASSWORD;

  public function __construct() {
    try {
      $this->conn = new PDO('mysql:dbname=' . $this->dbname . ';host=' . $this->servername, $this->username, $this->password);
    } catch(PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
  }

  public static function GetInstance() {
    if(static::$instance == null) {
      static::$instance = new static();
    }
    return static::$instance;
  }

  private function PrepQuery($query) {
    return $this->conn->prepare($query);
  }

  private function BindParam($instance, $bindArray) {
    foreach($bindArray as $key => &$value) {
      $instance->bindParam($key, $value);
    }
  }

  private function FullyPrepQuery($query, $bindArray) {
    $prepped = $this->PrepQuery($query);
    if(is_array($bindArray) && !empty($bindArray)) {
      $this->BindParam($prepped, $bindArray);
    }
    return $prepped;
  }

  public function ReadData($options = []) {
    $bindArray = (isset($options['bindParam'])) ? $options['bindParam'] : [];
    $prepped = $this->FullyPrepQuery($options['query'], $bindArray);
    $prepped->execute();
    $result = $prepped->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function CUD($options = []) {
    $bindArray = (isset($options['bindParam'])) ? $options['bindParam'] : [];
    $prepped = $this->FullyPrepQuery($options['query'], $bindArray);
    $prepped->execute();
    return $prepped->rowCount();
  }


  public function __destruct() {
    $this->conn = null;
  }
  
}
