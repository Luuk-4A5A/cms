<?php

Class DbHandler {
  private $conn;

  public function __construct($serverName, $databaseName, $username, $password) {
    try {
      $this->conn = new PDO('mysql:dbname=' . $databaseName . ';host=' . $serverName, $username, $password);
    } catch(PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
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
}
